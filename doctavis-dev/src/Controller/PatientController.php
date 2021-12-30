<?php


namespace App\Controller;


use App\Entity\AccessibilityCriterion;
use App\Entity\Equipment;
use App\Entity\ImportanceCriterion;
use App\Entity\Note;
use App\Entity\Pathology;
use App\Entity\Practitioner;
use App\Entity\PractitionerImportanceCriterion;
use App\Form\NoteStep2Type;
use App\Form\NoteType;
use App\Form\PractitionerFormType;
use App\Form\PractitionerRecommandationStep2FormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use const http\Client\Curl\PROXY_SOCKS4A;

class PatientController extends AbstractController
{

    private $em;

    /**
     * PatientController constructor.
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/search/recommend_practitioner", name="recommend_practitioner")
     */
    public function recommandation(Request $request)
    {
        $practitionerId = $request->query->get('practitioner');
        if (null == $practitionerId) {
            $practitioner = new Practitioner();
            $practitioner->setIsEnabled(false);
        } else {
            $practitioner = $this->em->getRepository(Practitioner::class)->findOneById($practitionerId);
        }

        $pathologies = $this->em->getRepository(Pathology::class)->getGroupedPathologies();

        $form = $this->createForm(PractitionerFormType::class, $practitioner, [
            "pathologies" => $pathologies
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            foreach ($form->get("unmappedPathologies")->getData() as $pathology) {
                $pathologyEntity = $this->em->getRepository(Pathology::class)->findOneById($pathology);
                $practitioner->addPathology($pathologyEntity);
            }
            $pract = $this->em->getRepository(Practitioner::class)->findOneBy(['firstname' => $data->getFirstname(), 'lastname' => $data->getLastname()/*, 'profession' => $data->getProfession()*/]);
            if (null === $pract) {
                $this->em->persist($practitioner);
                $this->em->flush();
                return $this->redirectToRoute('recommend_practitioner_step2', ['practitioner' => $practitioner->getId()]);
            } else {
                $this->em->persist($pract);
                $this->em->flush();
                return $this->redirectToRoute('recommend_practitioner_step2', ['practitioner' => $pract->getId()]);
            }
        }
        return $this->render('practitioner/recommend.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/search/recommend_practitioner_step2/{practitioner}", name="recommend_practitioner_step2")
     */
    public function recommandationStep2(Request $request, ?Practitioner $practitioner)
    {
        if (null === $practitioner) {
            throw new \Exception("Il n'y a pas de praticien désigné");
        }

        $importanceCriterions = $this->em->getRepository(ImportanceCriterion::class)->findAll();
        foreach ($importanceCriterions as $criterion) {
            $practitionerCriterion = new PractitionerImportanceCriterion();
            $practitionerCriterion->setCriterion($criterion);
            $practitioner->addPractitionerImportanceCriterion($practitionerCriterion);
        }

        $accessibilityCriterions = $this->em->getRepository(AccessibilityCriterion::class)->getGroupedCriterions();

        $form = $this->createForm(PractitionerRecommandationStep2FormType::class, $practitioner, [
            "accessibilityCriterions" => $accessibilityCriterions
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($practitioner->getAccessibilityCriterions() as $criterion) {
                $practitioner->removeAccessibilityCriterion($criterion);
            }
            foreach ($practitioner->getEquipments() as $equipment) {
                $practitioner->removeEquipment($equipment);
            }

            foreach ($accessibilityCriterions as $key => $accessibilityCriterion) {
                $datas = $form->get(self::slugify($key))->getData();
                if(!empty($data)) {
                    if(gettype($datas) === "array") {
                        if (count($datas) > 0) {
                            foreach ($datas as $data) {
                                $accessibilityCriterionEntity = $this->em->getRepository(AccessibilityCriterion::class)->findOneById($data);
                                $practitioner->addAccessibilityCriterion($accessibilityCriterionEntity);
                            }
                        }
                    } else {
                        $accessibilityCriterionEntity = $this->em->getRepository(AccessibilityCriterion::class)->findOneById($data);
                        $practitioner->addAccessibilityCriterion($accessibilityCriterionEntity);
                    }
                }
            }
            $equipments = $form->get("equipments")->getData();
            foreach ($equipments as $equipment) {
                if (count($equipment) > 0) {
                foreach ($equipment as $equipmentEntity) {
                        $practitioner->addEquipment($equipmentEntity);
                    }
                }
            }
            $this->em->persist($practitioner);
            $this->em->flush();
//            $this->addFlash('success', 'Votre recommandation a bien été prise en compte');
            return $this->redirectToRoute('confirmation');
        }
        return $this->render('practitioner/recommend_step2.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/search/note_practitioner", name="note_practitioner")
     */
    public function note(Request $request)
    {
        $session = $request->getSession();
        $session->set('searchData', []);
        $search = $request->get('search');
        $practitioners = $this->em->getRepository(Practitioner::class)->getPractitioners();

        $form = $this->createForm(NoteType::class, null, ['practitioners' => $practitioners]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $practitioner = $data['practitioner'][0];
            return $this->redirectToRoute('note_practitioner_step2', ['practitioner' => $practitioner->getId()]);
        }
        return $this->render('practitioner/note.html.twig', [
            'form' => $form->createView(),
            'search' => $search
        ]);
    }

    /**
     * @Route("/search/{practitioner}/note_practitioner", name="note_practitioner_step2")
     */
    public function noteStep2(Request $request, ?Practitioner $practitioner)
    {
        if (null === $practitioner) {
            throw new \Exception("Il n'y a pas de praticien désigné");
        }
        $session = $request->getSession();
        $session->set('searchData', []);

        $importanceCriterions = $this->em->getRepository(ImportanceCriterion::class)->findAll();
        foreach ($importanceCriterions as $criterion) {
            $practitionerCriterion = new PractitionerImportanceCriterion();
            $practitionerCriterion->setCriterion($criterion);
            $practitioner->addPractitionerImportanceCriterion($practitionerCriterion);
        }

        $form = $this->createForm(NoteStep2Type::class, $practitioner);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($practitioner);
            $this->em->flush();
/*            $this->addFlash('success', 'Votre recommendation a bien été prise en compte');*/
            return $this->redirectToRoute('confirmation');
        }
        return $this->render('practitioner/note_step2.html.twig', [
            'form' => $form->createView(),
            'firstname' => $practitioner->getFirstname(),
            'lastname' => $practitioner->getLastname(),
            'specialities' => $practitioner->getSpecialities()
        ]);
    }

    /**
     * @Route("/search/confirmation", name="confirmation")
     */
    public function confirmation(Request $request)
    {
        return $this->render('practitioner/confirmation.html.twig');
    }

    public static function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '_', $text);
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        // trim
        $text = trim($text, '_');
        // remove duplicate _
        $text = preg_replace('~-+~', '_', $text);
        // lowercase
        $text = strtolower($text);
        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}
