<?php


namespace App\Controller;


use App\Entity\AccessibilityCriterion;
use App\Entity\Equipment;
use App\Entity\ImportanceCriterion;
use App\Entity\Pathology;
use App\Entity\Practitioner;
use App\Entity\PractitionerImportanceCriterion;

//use App\Entity\Profession;
use App\Entity\Speciality;
use App\Form\PractitionerFormStep1Type;
use App\Form\PractitionerFormStep2Type;
use App\Form\PractitionerFormStep3BisType;
use App\Form\PractitionerFormStep3Type;
use App\Form\PractitionerFormType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PractitionerController extends AbstractController
{
    /**
     * @Route("/practitioner/index", name="practitioner_index")
     */
    public function index(Request $request)
    {
        $user = $this->getUser();
        return $this->render('practitioner/index.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/practitioner/new", name="practitioner_new")
     */
    public function new(Request $request, EntityManagerInterface $em)
    {
        $user = $this->getUser();
        $practitioner = new Practitioner();
        $user->setPractitioner($practitioner);
//        $importanceCriterions = $em->getRepository(ImportanceCriterion::class)->findAll();
//        foreach ($importanceCriterions as $criterion) {
//            $practitionerCriterion = new PractitionerImportanceCriterion();
//            $practitionerCriterion->setCriterion($criterion);
//            $em->persist($practitionerCriterion);
//            $practitioner->addPractitionerImportanceCriterion($practitionerCriterion);
//        }
        $form = $this->createForm(PractitionerFormStep1Type::class, $practitioner);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($practitioner);
            $user->setPractitioner($practitioner);
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Votre fiche professionnelle a été créée avec succes');

            $session = $request->getSession();
            $session->set('new','true');
            return $this->redirectToRoute('practitioner_edit_step2');
        }
        return $this->render('practitioner/new.html.twig', [
            'form' => $form->createView(),
            'step' => 1,
            'new' => true]);
    }


    /**
     * @Route("/practitioner/edit/step1", name="practitioner_edit_step1")
     */
    public
    function editStep1(Request $request, EntityManagerInterface $em)
    {
        $user = $this->getUser();
        $practitioner = $user->getPractitioner();
        if (null === $practitioner) {
            return $this->redirectToRoute('practitioner_new');
        }
        $form = $this->createForm(PractitionerFormStep1Type::class, $practitioner);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($practitioner);
            $em->flush();
            return $this->redirectToRoute('practitioner_edit_step2');
        }
        return $this->render('practitioner/edit.html.twig', [
            'form' => $form->createView(),
            'step' => 1,
            'new' => false
        ]);
    }

    /**
     * @Route("/practitioner/edit/step2", name="practitioner_edit_step2")
     */
    public
    function editStep2(Request $request, EntityManagerInterface $em)
    {
        $session = $request->getSession();
        $new = $session->get('new');
        $user = $this->getUser();
        /** @var Practitioner $practitioner */
        $practitioner = $user->getPractitioner();

        $pathologies = $em->getRepository(Pathology::class)->getGroupedPathologies();
        $mainSpecialities = $em->getRepository(Speciality::class)->getMains();
        $specialities = $em->getRepository(Speciality::class)->getGroupedSpecialities();
        $practitionerData = [];

        foreach ($practitioner->getPathologies() as $pathology) {
            $practitionerData["pathologies"][$pathology->getName()] = $pathology->getId();
        }
        foreach ($practitioner->getSpecialities() as $speciality) {
            if ($speciality->getLevel() === 1) {
                $practitionerData["mainSpecialities"][$speciality->getName()] = $speciality->getId();
            }
            $practitionerData["specialities"][$speciality->getName()] = $speciality->getId();
        }

        $form = $this->createForm(PractitionerFormStep2Type::class, $practitionerData,[
            "pathologies" => $pathologies,
            'mainSpecialities' => $mainSpecialities,
            'specialities' => $specialities
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            foreach ($practitioner->getPathologies() as $pathology) {
                $practitioner->removePathology($pathology);
            }
            foreach ($practitioner->getSpecialities() as $speciality) {
                $practitioner->removeSpeciality($speciality);
            }

            foreach ($data["pathologies"] as $pathology) {
                $pathologyEntity = $em->getRepository(Pathology::class)->findOneById($pathology);
                $practitioner->addPathology($pathologyEntity);
            }
            foreach ($data["specialities"] as $speciality) {
                $specialityEntity = $em->getRepository(Speciality::class)->findOneById($speciality);
                $practitioner->addSpeciality($specialityEntity);
            }
            foreach ($data["mainSpecialities"] as $speciality) {
                $specialityEntity = $em->getRepository(Speciality::class)->findOneById($speciality);
                $practitioner->addSpeciality($specialityEntity);
            }
            $em->persist($practitioner);
            $em->flush();

            if ("true" === $new) {
                $session->set('new','false');
                return $this->redirectToRoute('welcome');
            }
            return $this->redirectToRoute('practitioner_edit_step3');
        }
        return $this->render('practitioner/edit.html.twig', [
            'form' => $form->createView(),
            'step' => 2,
            'new' => $new
        ]);
    }

    /**
     * @Route("/practitioner/edit/step3", name="practitioner_edit_step3")
     */
    public function editStep3(Request $request, EntityManagerInterface $em)
    {
        $user = $this->getUser();
        /** @var Practitioner $practitioner */
        $practitioner = $user->getPractitioner();
        $form = $this->createForm(PractitionerFormStep3Type::class, $practitioner);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($practitioner);
            $em->flush();

            if (in_array("En cabinet",$practitioner->getConsultationTypes(), true)) {
                return $this->redirectToRoute('practitioner_edit_step3_bis');
            }
            $this->addFlash('success', 'Votre fiche professionnelle a été mise à jour avec succes');
            return $this->redirectToRoute('practitioner_profile');
        }
        return $this->render('practitioner/edit.html.twig', [
            'form' => $form->createView(),
            'step' => 3
        ]);
    }

    /**
     * @Route("/practitioner/edit/step3bis", name="practitioner_edit_step3_bis")
     */
    public function editStep3Bis(Request $request, EntityManagerInterface $em)
    {
        $user = $this->getUser();
        /** @var Practitioner $practitioner */
        $practitioner = $user->getPractitioner();
        $accessibilityCriterions = $em->getRepository(AccessibilityCriterion::class)->getGroupedCriterions();
        $accessibilityCriterionsDatas = [];
        $equipments = "";

        $form = $this->createForm(PractitionerFormStep3BisType::class, null, [
            'accessibilityCriterions' => $accessibilityCriterions
        ]);

        foreach ($practitioner->getEquipments() as $equipment) {
            $equipments .= $equipment->getName().";";
        }
        $equipments = substr($equipments, 0, -1);
        $form->get('equipments')->setData($equipments);
        foreach ($practitioner->getAccessibilityCriterions() as $accessibilityCriterion) {
            $parentName = $accessibilityCriterion->getParent()->getName();
            if( $parentName === "ASCENSEUR" || $parentName === "ACCESSIBLE EN TRANSPORT EN COMMUN"){
                $accessibilityCriterionsDatas[$accessibilityCriterion->getParent()->getName()] = $accessibilityCriterion->getId();
            } else {
                $accessibilityCriterionsDatas[$accessibilityCriterion->getParent()->getName()][] = $accessibilityCriterion->getId();
            }
        }

        foreach ($accessibilityCriterionsDatas as $key => $accessibility) {
            $form->get(self::slugify($key))->setData($accessibility);
        }

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
                if(gettype($datas) === "array") {
                    if (count($datas) > 0) {
                        foreach ($datas as $data) {
                            $accessibilityCriterionEntity = $em->getRepository(AccessibilityCriterion::class)->findOneById($data);
                            $practitioner->addAccessibilityCriterion($accessibilityCriterionEntity);
                        }
                    }
                } else {
                    $accessibilityCriterionEntity = $em->getRepository(AccessibilityCriterion::class)->findOneById($datas);
                    $practitioner->addAccessibilityCriterion($accessibilityCriterionEntity);
                }
            }
            $equipments = $form->get("equipments")->getData();
            $equipments = explode(';', $equipments);
            foreach ($equipments as $equipment) {
                $equipmentExists = $em->getRepository(Equipment::class)->findOneBy(['name' => trim($equipment)]);
                if($equipmentExists){
                    $practitioner->addEquipment($equipmentExists);
                } else {
                    $equipmentEntity = new Equipment(trim($equipment));
                    $em->persist($equipmentEntity);
                    $practitioner->addEquipment($equipmentEntity);
                }
            }

            $em->persist($practitioner);
            $em->flush();
            $this->addFlash('success', 'Votre fiche professionnelle a été mise à jour avec succes');
            return $this->redirectToRoute('practitioner_profile');
        }
        return $this->render('practitioner/edit.html.twig', [
            'form' => $form->createView(),
            'step' => '3bis'
        ]);
    }

    /**
     * @Route("/practitioner/profile/show", name="practitioner_profile")
     */
    public function show(Request $request)
    {
        $manager = $this->getDoctrine();
        $user = $this->getUser();
        $practitioner = $user->getPractitioner();
        $criterions = $manager->getRepository(ImportanceCriterion::class)->findAll();
        $importanceCriterions = $manager->getRepository(PractitionerImportanceCriterion::class)->getAverageNote($practitioner, $criterions);
        return $this->render('practitioner/show.html.twig', [
            'practitioner' => $practitioner,
            'importanceCriterions' => $importanceCriterions,
        ]);
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