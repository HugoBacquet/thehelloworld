<?php


namespace App\Controller\Admin;

use App\Entity\Practitioner;
use App\Form\ImportPractitionerType;
use App\Form\PractitionerFormType;
use App\Service\ImportPractitioner;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AdminController extends AbstractController
{
    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    /**
     * AdminController constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("admin/practitioner/new", name="add_practitioner")
     */
    public function index(Request $request, EntityManagerInterface  $em)
    {
        $practitioner =  new Practitioner();
        $form = $this->createForm(PractitionerFormType::class, $practitioner);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($practitioner);
            $em->flush($practitioner);
            return $this->redirectToRoute('admin');
        }
        return $this->render('admin/new_practitioner.html.twig',
            [
                'form' => $form->createView()
            ]);
    }


    /**
     * @Route("admin/practitioner/import", name="import_practitioners")
     */
    public function import(Request $request, ImportPractitioner $importPractitioner)
    {
        $form = $this->createForm(ImportPractitionerType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $file = $form->getData()["file"];
            if ($file->guessExtension() != "txt") {
                $this->addFlash('error', 'Ficher CSV demandé');
            } else {
                $content = $importPractitioner->import($file, $request);
                switch ($content) {
                    case !empty($content["success"]):
                        $this->addFlash('success', $content["success"]);
                        break;
                    case !empty($content["warning"]):
                        $this->addFlash('warning', $content["warning"]);
                        break;
                    case !empty($content["error"]):
                        $this->addFlash('error', $content["error"]);
                        break;
                }
            }
            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/import_practitioners.html.twig',
            [
                'form' => $form->createView()
            ]);
    }

    /**
     * @Route("/admin/export/{entity}", name="export")
     *
     * @param string $entity
     * @param NormalizerInterface $normalizer
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function exportAction(string $entity, NormalizerInterface $normalizer){
        $delimiter = ';';
        $enclosure = '"';
        $fileName = 'doctavis_'.$entity;
        $filePath = tempnam(sys_get_temp_dir(), $fileName);

        $datas = $this->entityManager->getRepository('App\\Entity\\'.ucfirst($entity))->findAll();
        if (null === $datas) {
            throw new \Exception('No data found');
        }
        $file = fopen($filePath, 'w');
        foreach($datas as $data) {
            $csv = $normalizer->normalize($data, 'csv', ['groups' => 'export-'.$entity]);
            // First line of csv
            $header = "";
            foreach ($csv as $key => &$field) {
                // Check relations in array
                if (is_array($field)) {
                    foreach ($field as &$subField) {
                        // Check OneToMany relations in relations
                        if (is_array($subField)) {
                            $subField = implode('/',$subField);
                        }
                    }
                    $field = implode('/',$field);
                }

                $header .= $key.$delimiter;
            }

            // Writing first line
            if ("" === file_get_contents($filePath)) {
                fwrite($file, $header.PHP_EOL);
            }
            // Writing csv data line
            fputcsv($file,$csv,$delimiter,$enclosure);
        }
        fclose($file);

        // Check if problem with file
        if (!is_file($filePath)) {
            throw new \Exception('This file does not exist');
        }
        // Return CSV as download
        return $this->file($filePath, $fileName.'.csv');
    }
}