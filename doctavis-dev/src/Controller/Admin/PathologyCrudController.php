<?php

namespace App\Controller\Admin;

use App\Entity\Pathology;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PathologyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Pathology::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            IntegerField::new('level'),
            AssociationField::new('parent'),
        ];
    }
}
