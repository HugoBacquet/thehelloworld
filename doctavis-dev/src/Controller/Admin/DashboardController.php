<?php

namespace App\Controller\Admin;

use App\Entity\AccessibilityCriterion;
use App\Entity\AdditionalCriterion;
use App\Entity\Care;
use App\Entity\Equipment;
use App\Entity\Formation;
use App\Entity\ImportanceCriterion;
use App\Entity\Language;
use App\Entity\Pathology;
use App\Entity\Patient;
use App\Entity\Practitioner;
//use App\Entity\Profession;
use App\Entity\Speciality;
use App\Entity\Temperament;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();
        return $this->redirect($routeBuilder->setController(AccessibilityCriterionCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Doctavis Admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Critères d\'accessibilité', 'fa fa-List', AccessibilityCriterion::class);
        yield MenuItem::linkToCrud('Critères additionnels', 'fa fa-List', AdditionalCriterion::class);
        yield MenuItem::linkToCrud('Equipements', 'fa fa-List', Equipment::class);
        yield MenuItem::linkToCrud('Critères d\'importances', 'fa fa-List', ImportanceCriterion::class);
//        yield MenuItem::linkToCrud('Professions', 'fa fa-List', Profession::class);
        yield MenuItem::linkToCrud('Languages', 'fa fa-List', Language::class);
        yield MenuItem::linkToCrud('Pathologies', 'fa fa-List', Pathology::class);
        yield MenuItem::linkToCrud('Spécialités', 'fa fa-List', Speciality::class);
        yield MenuItem::linkToCrud('Tempéraments', 'fa fa-List', Temperament::class);
        yield MenuItem::linkToCrud('Patients', 'fa fa-List', Patient::class);
        yield MenuItem::linkToCrud('Praticiens', 'fa fa-List', Practitioner::class);
        yield MenuItem::linkToRoute('Importer des praticiens', 'fa fa-List', 'import_practitioners');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
