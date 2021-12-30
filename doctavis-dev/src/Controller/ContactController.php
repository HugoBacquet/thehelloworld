<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request)
    {
        return $this->render('contact/index.html.twig');
    }

    /**
     * @Route("/contact-outils", name="contact_outils")
     */
    public function contactOutils(Request $request)
    {
        return $this->render('contact/outils.html.twig');
    }

    /**
     * @Route("/contact-speciality-pathology", name="contact_speciality_pathology")
     */
    public function contactSpeciality(Request $request)
    {
        return $this->render('contact/speciality_pathology.html.twig');
    }
}