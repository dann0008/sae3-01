<?php

namespace App\Controller\Admin;

use App\Entity\Calendrier;
use App\Repository\UtilisateurRepository;
use App\Repository\ClasseRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CalendrierCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Calendrier::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre'),
            TextField::new('description'),
            DateTimeField::new('debut'),
            DateTimeField::new('fin'),
            BooleanField::new('all_day'),
            ColorField::new('couleur'),
            ];
    }

}
