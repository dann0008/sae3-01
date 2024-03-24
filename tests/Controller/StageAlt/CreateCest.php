<?php

namespace App\Tests\Controller\StageAlt;

use App\Tests\Support\ControllerTester;

class CreateCest
{
    public function form(ControllerTester $I): void
    {
        $I->amOnPage('/stageAlt_create');

        $I->seeInTitle("Création d'un nouveau Stage/Alternance");
        $I->see("Création d'un nouveau Stage/Alternance", 'h1');
    }
}
