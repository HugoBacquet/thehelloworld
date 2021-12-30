<?php

namespace App\Controller\Admin;

use App\Entity\AdditionalCriterion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AdditionalCriterionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AdditionalCriterion::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
