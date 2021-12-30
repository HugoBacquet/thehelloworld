<?php

namespace App\Controller\Admin;

use App\Entity\Temperament;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TemperamentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Temperament::class;
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
