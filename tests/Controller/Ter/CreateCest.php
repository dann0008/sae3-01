<?php

namespace App\Tests\Controller\Ter;

use App\Tests\Support\ControllerTester;

class CreateCest
{
    public function form(ControllerTester $I): void
    {
        $I->amOnPage('/ter_create');

        $I->seeInTitle("Création d'un nouveau TER");
        $I->see("Création d'un nouveau TER", 'h1');
    }
}
