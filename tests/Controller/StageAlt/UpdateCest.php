<?php

namespace App\Tests\Controller\StageAlt;

use App\Factory\StageAltFactory;
use App\Tests\Support\ControllerTester;

class UpdateCest
{
    public function form(ControllerTester $I): void
    {
        StageAltFactory::createOne([
            'nom' => 'Stage info',
        ]);

        $I->amOnPage('/stagealt/1/update');

        $I->seeInTitle('Édition de Stage info');
        $I->see('Édition de Stage info', 'h1');
    }
}
