<?php

namespace App\Controller\Admin;

use App\Entity\Speciality;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SpecialityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Speciality::class;
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
