<?php


namespace App\Tests\Controller\StageAlt;

use App\Tests\Support\ControllerTester;

class IndexCest
{
    public function _before(ControllerTester $I)
    {
    }

    // tests
    public function TestPageStageAlt(ControllerTester $I): void
    {
        $I->amOnPage('/stagealt');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Liste des Stages/Alternances');
        $I->see('Liste des Stages/Alternances', 'h1');
    }
}
