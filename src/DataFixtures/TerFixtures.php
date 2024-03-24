<?php

namespace App\DataFixtures;

use App\Factory\TerFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TerFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        TerFactory::createMany(20);

    }

    public function getDependencies() : array
    {
        return [
            UtilisateurFixtures::class,
        ];
    }
}
