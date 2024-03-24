<?php

namespace App\Controller\Admin;

use App\Entity\Classe;
use App\Repository\CalendrierRepository;
use App\Repository\UtilisateurRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ClasseCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Classe::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            ChoiceField::new('niveau')->setChoices([
                'Master 1' => 'M1',
                'Master 2' => 'M2',
            ]),
            AssociationField::new('cours')
                ->setFormTypeOptions(['choice_label' => 'titre'])
                ->setRequired(false),
        ];
    }

}
