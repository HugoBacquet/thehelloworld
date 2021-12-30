<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EReputationController extends AbstractController
{
    /**
     * @Route("/practitioner/ereputation", name="ereputation_index")
     */
    public function index(){
        return $this->render('ereputation/index.html.twig');
    }
}