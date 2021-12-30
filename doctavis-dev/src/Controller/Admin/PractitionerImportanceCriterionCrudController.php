<?php

namespace App\Controller\Admin;

use App\Entity\PractitionerImportanceCriterion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class PractitionerImportanceCriterionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PractitionerImportanceCriterion::class;
    }


//    public function configureFields(string $pageName): iterable
//    {
//        return [
////            IdField::new('id'),
////            TextField::new('title'),
////            TextEditorField::new('description'),
//        AssociationField::new('criterion')
//        ];
//    }

}
