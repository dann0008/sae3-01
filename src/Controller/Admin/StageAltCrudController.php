<?php

namespace App\Controller\Admin;

use App\Entity\StageAlt;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use App\Repository\UtilisateurRepository;

class StageAltCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return StageAlt::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            TextField::new('description'),
            ChoiceField::new('type')->setChoices([
                'Alternance' => 'Alternance',
                'Stage' => 'Stage',
            ]),
            DateField::new('dateDebut'),
            DateField::new('dateFin'),
            AssociationField::new('entreprise')
                ->setFormTypeOptions(['choice_label' => 'nom',
                    'query_builder' => function(UtilisateurRepository $utilisateurRepository) {
                    return $utilisateurRepository->createQueryBuilder('entity')
                        ->where('entity.roles LIKE :role')
                        ->setParameter('role', '%"ROLE_ENTREPRISE"%')
                        ->orderBy('entity.nom, entity.prenom');
                    }])
                ->formatValue(function ($value, $entity) {
                    return $entity?->getEntreprise()?->getNom().' '.$entity?->getEntreprise()?->getPrenom();}),
            AssociationField::new('candidats')
            ->setFormTypeOptions(['choice_label' => 'nom',
                'query_builder' => function(UtilisateurRepository $utilisateurRepository) {
                    return $utilisateurRepository->createQueryBuilder('entity')
                        ->leftJoin('entity.stageAlt','stageAlt')
                        ->where('entity.roles LIKE :role')
                        ->andWhere('stageAlt is null')
                        ->setParameter('role', '%"ROLE_ETUDIANT"%')
                        ->orderBy('entity.nom, entity.prenom');
                }]),
        ];
    }

}
