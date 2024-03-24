<?php

namespace App\DataFixtures;

use App\Factory\UtilisateurFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UtilisateurFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        UtilisateurFactory::createMany(10);

        UtilisateurFactory::createOne(['roles' => ['ROLE_ETUDIANT'],'nom' => 'Etudiant', 'prenom' => 'Test', 'email' => 'etud@test.fr']);
        UtilisateurFactory::createOne(['roles' => ['ROLE_PROFESSEUR'],'nom' => 'Professeur', 'prenom' => 'Test', 'email' => 'prof@test.fr']);
        UtilisateurFactory::createOne(['roles' => ['ROLE_ENTREPRISE'],'nom' => 'Entreprise', 'email' => 'entr@test.fr']);
        UtilisateurFactory::createOne(['roles' => ['ROLE_ADMIN','ROLE_PROFESSEUR'],'nom' => 'Admin', 'prenom' => 'Test', 'email' => 'admin@test.fr']);

    }

    public function getDependencies() : array
    {
        return [
            ClasseFixtures::class,
            CalendrierFixtures::class
        ];
    }
}
