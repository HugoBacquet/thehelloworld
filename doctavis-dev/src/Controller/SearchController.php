<?php

namespace App\Controller;

use App\Constant\ConsultationTypes;
use App\Constant\Sector;
use App\Entity\AccessibilityCriterion;
use App\Entity\Care;
use App\Entity\ImportanceCriterion;
use App\Entity\Patient;
use App\Entity\Practitioner;
use App\Entity\PractitionerImportanceCriterion;
use App\Entity\Speciality;
use App\Form\PatientType;
use App\Form\SearchPractitionerStep2Type;
use App\Form\SearchPractitionerType;
use App\Form\SpecialityType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    private $em;

    /**
     * SearchController constructor.
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/search/results", name="search_results_index")
     */
    public function index(Request $request)
    {
        $session = $this->container->get('session');
        $data = $session->get('searchData');
        if ($data == []) {
            return $this->redirectToRoute('home');
        }
        $results = $this->em->getRepository(Practitioner::class)->getData($data);
        $session->set('searchData', []);
        return $this->render('search/index.html.twig', [
            'results' => $results,
            'speciality' => $data['specialities']
        ]);
    }

    /**
     * @Route("/search/criterions", name="search_by_criterions")
     */
    public function search(Request $request, EntityManagerInterface $entityManager)
    {

        $session = $this->container->get('session');
        $searchData = $session->get('searchData') ?: [];
        $speciality = [];
        if (isset($searchData['specialities'])) {
            if (!is_array($searchData["specialities"])) {
                $speciality = $entityManager->getRepository(Speciality::class)->findOneById($searchData['specialities']);
            }
        } else {
            $speciality = $entityManager->getRepository(Speciality::class)->findOneByName("Généraliste");
        }

        $form = $this->createForm(SearchPractitionerType::class, null);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $searchData = array_merge($searchData, $data);
            $searchData['specialities'] = $speciality;
            $session->set('searchData', $searchData);

            $practitioners = $this->em->getRepository(Practitioner::class)->getData($searchData);
            $session->set('searchCount', count($practitioners));

            foreach ($data['consultationType'] as $consultationType) {
                if (ConsultationTypes::getNameByNumber($consultationType) === 'En cabinet') {
                    return $this->redirectToRoute('search_additionnal_criterions');
                }
            }
            return $this->redirectToRoute('patient_informations');

        }
        return $this->render('search/form.html.twig', [
            'form' => $form->createView(),
            'speciality' => $speciality,
        ]);
    }

    /**
     * @Route("/search/accessibility-criterions", name="search_additionnal_criterions")
     */
    public function searchStep2(Request $request)
    {
        $session = $request->getSession();
        $searchData = $session->get('searchData') ?: [];
        $accessibilityCriterions = $this->em->getRepository(AccessibilityCriterion::class)->getGroupedCriterions();

        $form = $this->createForm(SearchPractitionerStep2Type::class, null, [
            "accessibilityCriterions" => $accessibilityCriterions
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $searchData = array_merge($data, $searchData);
            $session->set('searchData', $searchData);
            if ($form->getClickedButton() === $form->get('submit')) {
                return $this->redirectToRoute('patient_informations');
            } else {
                return $this->redirectToRoute('search_by_criterions');
            }
        }
        return $this->render('search/form_step2.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/search/patient", name="patient_informations");
     */
    public function patient(Request $request, EntityManagerInterface $em, \Swift_Mailer $mailer)
    {
        $session = $request->getSession();
        $searchCount = $session->get('searchCount');

        $patient = new Patient();
        $form = $this->createForm(PatientType::class, $patient);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($patient);
            $em->flush();

//            // Mail confirmation Zoe
            $confirmationEmail = (new \Swift_Message("Un patient a réalisé une requête"))
                ->setFrom($_ENV['MAIL_ADDRESS'])
                ->setTo($_ENV['MAIL_ADDRESS'])
                ->setBody(
                    $this->renderView(
                        'emails/new_patient.html.twig',
                        [
                            'username' => $patient->getEmail()
                        ]
                    ),
                    'text/html'
                );

            $mailer->send($confirmationEmail);

            if ($searchCount > 0) {
                return $this->redirectToRoute('search_results_index');
            } else {
                return $this->redirectToRoute('note_practitioner', ['search' => 'true']);
            }
        }

        return $this->render('patient/new.html.twig', [
            'count' => $searchCount,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/search/appointment", name="ask_for_appointment");
     */
    public function appointment(Request $request)
    {

        if ($request->query->has('practitionerId')) {
            $id = $request->query->get('practitionerId');
            $practitioner = $this->em->getRepository(Practitioner::class)->find(['id' => $id]);
        } else {
            return $this->redirectToRoute('home');
        }

        $accessibilityCriterions = $this->em->getRepository(AccessibilityCriterion::class)->findAll();
        $criterions = $this->em->getRepository(ImportanceCriterion::class)->findAll();
        $importanceCriterions = $this->em->getRepository(PractitionerImportanceCriterion::class)->getAverageNote($practitioner, $criterions);

        return $this->render('practitioner/show.html.twig', [
            'practitioner' => $practitioner,
            'accessibilityCriterions' => $accessibilityCriterions,
            'importanceCriterions' => $importanceCriterions,
            'sex' => $practitioner->getSex(),
            'sector' => Sector::getNameByNumber($practitioner->getSector())
        ]);
    }

    /**
     * @Route("/search/speciality", name="search_speciality");
     */
    public function searchSpeciality(Request $request)
    {
        $session = $request->getSession();
        $searchData = $session->get('searchData');

        $specialities = $this->em->getRepository(Speciality::class)->getGroupedSpecialities();

        $form = $this->createForm(SpecialityType::class, null, [
            'specialities' => $specialities
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $searchData["specialities"] = $data["specialities"];
            $session->set('searchData', $searchData);
            $speciality = $data["specialities"];
            $practitioner = $this->em->getRepository(Practitioner::class)->getPractitionersBySpecialty($speciality);
            $session->set('searchCount', count($practitioner));
            return $this->redirectToRoute('search_by_criterions');
        }

        return $this->render('search/speciality.html.twig',
            [
                'specialities' => $specialities,
                'form' => $form->createView()
            ]);
    }

    /**
     * @Route("/search/generalist", name="search_generalist");
     */
    public function searchGeneralist(Request $request)
    {
        $session = $request->getSession();
        $practitioner = $this->em->getRepository(Practitioner::class)->getPractitionersBySpecialty("Généraliste");
        $session->set('searchCount', count($practitioner));
        return $this->render('search/generalist.html.twig');
    }
}