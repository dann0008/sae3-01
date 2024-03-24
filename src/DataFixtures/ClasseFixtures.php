<?php

namespace App\DataFixtures;

use App\Factory\ClasseFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ClasseFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        ClasseFactory::createMany(6);
    }

    public function getDependencies() : array
    {
        return [
            CalendrierFixtures::class
        ];
    }
}
