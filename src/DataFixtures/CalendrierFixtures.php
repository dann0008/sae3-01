<?php

namespace App\DataFixtures;

use App\Factory\CalendrierFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CalendrierFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        CalendrierFactory::createMany(300);
    }

}
