<?php

namespace App\DataFixtures;


use App\Factory\StageAltFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class StageAltFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        StageAltFactory::createMany(15);

    }

    public function getDependencies(): array
    {
        return [
            UtilisateurFixtures::class
        ];
    }
}
