<?php

namespace App\Controller\Admin;

use App\Entity\Ter;
use App\Repository\UtilisateurRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TerCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Ter::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom_sujet'),
            TextField::new('description'),
            AssociationField::new('createur')
                ->setFormTypeOptions(['choice_label' => 'nom',
                    'query_builder' => function(UtilisateurRepository $utilisateurRepository) {
                        return $utilisateurRepository->createQueryBuilder('entity')
                            ->where('entity.roles LIKE :role')
                            ->setParameter('role', '%"ROLE_PROFESSEUR"%');
                }])
                ->formatValue(function ($value, $entity) {
                    return $entity?->getCreateur()?->getNom().' '.$entity?->getCreateur()?->getPrenom();}),
            AssociationField::new('CandidatsInteresed')
                ->setFormTypeOptions(['choice_label' => 'nom',
                    'query_builder' => function(UtilisateurRepository $utilisateurRepository) {
                        return $utilisateurRepository->createQueryBuilder('entity')
                            ->leftJoin('entity.ter','ter')
                            ->where('entity.roles LIKE :role')
                            ->andWhere('ter is null')
                            ->setParameter('role', '%"ROLE_ETUDIANT"%');
                    }])
            ,
            ];
    }

}