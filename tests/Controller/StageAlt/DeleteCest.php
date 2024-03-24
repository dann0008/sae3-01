<?php

namespace App\Tests\Controller\StageAlt;

use App\Factory\StageAltFactory;
use App\Tests\Support\ControllerTester;

class DeleteCest
{
    public function form(ControllerTester $I): void
    {
        StageAltFactory::createOne([
            'nom' => 'Stage info',
        ]);

        $I->amOnPage('/stagealt/1/delete');

        $I->seeInTitle('Suppression de Stage info');
        $I->see('Suppression de Stage info', 'h1');
    }
}
