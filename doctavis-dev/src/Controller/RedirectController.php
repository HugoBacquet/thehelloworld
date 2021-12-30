<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RedirectController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        if ($this->isGranted('ROLE_ADMIN')){
            return $this->forward('App\Controller\Admin\DashboardController::index');
        } else {
            return $this->forward('App\Controller\PractitionerController::index');
        }
        return $this->render('index.html.twig');
    }
}