<?php

namespace App\Controller\Admin;

use App\Entity\Practitioner;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PractitionerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Practitioner::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            BooleanField::new('isEnabled'),
            IntegerField::new('postalCode'),
            TextField::new('firstname'),
            TextField::new('lastname'),
            TextField::new('address'),
            TextField::new('city'),
            TextField::new('phoneNumber'),
            TextField::new('email'),
            DateField::new('dateOfBirth'),
            TextField::new('placeOfBirth'),
            ChoiceField::new('sex')->setChoices([
                'Homme' => 'Homme',
                'Femme' => 'Femme',
                'Non binaire' => 'Non binaire',
            ]),
            AssociationField::new('pathologies'),
            AssociationField::new('specialities'),
            TextField::new('immatriculation'),
            IntegerField::new('experience'),
            TextField::new('sector'),
            ArrayField::new('consultationTypes'),
            AssociationField::new('languages'),
            ArrayField::new('paymentMethods'),
            AssociationField::new('equipments'),
            AssociationField::new('specialities'),
            AssociationField::new('accessibilityCriterions'),
            AssociationField::new('additionalCriterions'),
            BooleanField::new('isEmergencyAccepted'),
//            CollectionField::new('practitionerImportanceCriterions'),
        ];
    }
    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions);
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud->overrideTemplate('layout','admin/practitioner_index.html.twig');
        return parent::configureCrud($crud);
    }
}
