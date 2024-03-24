<?php

namespace App\Controller\Admin;

use App\Entity\Utilisateur;
use App\Repository\StageAltRepository;
use App\Repository\TerRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\FileUploadType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateurCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher) {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public static function getEntityFqcn(): string
    {
        return Utilisateur::class;
    }

    /**
     * Création de la méthode setUtilisateurPassword. Cette fonction récupère le mot de passe
     * qui est entré par l'administrateur dans le formulaire du back-office, si ce mot de passe
     * n'est pas vide, il est hasher avant d'être retourné.
     *
     * @param $entityInstance
     * @return string mot de passe hasher.
     */
    public function setUtilisateurPassword($entityInstance) : string {
        $password = $this->getContext()->getRequest()->get('Utilisateur')['password'];
        if (!empty($password)) {
            $password = $this->userPasswordHasher->hashPassword($entityInstance, $password);
        }
        return $password;
    }

    /**
     * Surcharge de la méthode. Cette fonction récupère le mot de passe
     * qui est entré par l'administrateur dans le formulaire du back-office lors de la modification d'un
     * utilisateur, si ce mot de passe n'est pas vide, il est hasher avant d'être donné comme valeur
     * à l'entité d'utilisateur courante.
     *
     * @param $entityInstance
     * @param EntityManagerInterface $entityManager
     */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $password = $this->getContext()->getRequest()->get('Utilisateur')['password'];
        if (!empty($password)) {
            $entityInstance->setPassword($this->userPasswordHasher->hashPassword($entityInstance, $password));
        }
        parent::updateEntity($entityManager, $entityInstance);
    }

    /**
     * Surcharge de la méthode persistEntity. Cette fonction récupère le mot de passe
     * qui est entré par l'administrateur dans le formulaire du back-office lors de la création d'un
     * utilisateur, si ce mot de passe n'est pas vide, il est hasher avant d'être donné comme valeur
     * à l'entité d'utilisateur courante.
     *
     * @param $entityInstance
     * @param EntityManagerInterface $entityManager
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setPassword($this->setUtilisateurPassword($entityInstance));
        parent::persistEntity($entityManager, $entityInstance);
    }

    /**
     * Surcharge de la méthode configureFields. Cette fonction configure tous les champs qui
     * seront dans le formulaire du back-office.
     *
     * @param string $pageName
     * @return iterable Champs du formulaire
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            EmailField::new('email'),
            ArrayField::new('roles'),
            TextField::new('password')
                ->setFormType(PasswordType::class)
                ->onlyOnForms()
                ->setEmptyData('')
                ->setRequired(false)
                ->setFormTypeOptions(['attr'=>['autocomplete' => 'off'],'mapped' => false,]),
            TextField::new('nom'),
            TextField::new('prenom'),
            TextField::new('adresse'),
            TextField::new('code_postal'),
            TextField::new('ville'),
            TelephoneField::new('telephone'),
            ChoiceField::new('sexe')->setChoices([
                'Femme' => 'F',
                'Homme' => 'H',
            ]),
            ImageField::new('photo')
                ->setFormType(FileUploadType::class)
                ->setBasePath('uploads/photo')
                ->setUploadDir('public/uploads/photos')
                ->setFormTypeOptions(['attr' =>[
                    'accept' => 'image/jpeg',
                                'image/png',
                ]])
                ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
                ->hideOnIndex(),
            ImageField::new('cv')
                ->setFormType(FileUploadType::class)
                ->setBasePath('uploads/cvs')
                ->setUploadDir('public/uploads/cvs')
                ->setFormTypeOptions(['attr' => [
                    'accept' => 'application/pdf'
                ]])
                ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
                ->hideOnIndex(),
            AssociationField::new('classes')
                ->setFormTypeOptions(['choice_label' => 'nom'])
                ->setRequired(false),
            AssociationField::new('cours')
                ->setFormTypeOptions(['choice_label' => 'titre'])
                ->setRequired(false),
            AssociationField::new('ter')
                ->setFormTypeOptions(['choice_label' => 'nomSujet'])
                ->setRequired(false)
                ->formatValue(function ($value, $entity) {
                    return $entity?->getTer()?->getNomSujet();}),
            AssociationField::new('stageAlt')
                ->setFormTypeOptions(['choice_label' => 'nom'])
                ->setRequired(false)
                ->formatValue(function ($value, $entity) {
                    return $entity?->getStageAlt()?->getNom();}),
        ];
    }
}
