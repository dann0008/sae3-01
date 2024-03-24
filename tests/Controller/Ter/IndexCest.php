<?php


namespace App\Tests\Controller\Ter;

use App\Factory\TerFactory;
use App\Tests\Support\ControllerTester;

class IndexCest
{
    public function TestTitle(ControllerTester $I)
    {
        $I->amOnPage('/ters');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Liste des Ters');
        $I->see('Liste des Ters', 'h1');
    }

    public function TestFactory(ControllerTester $I){
        TerFactory::createOne(['nomsujet'=> 'machin truc']);
        $I->click('machin truc');
        $I->seeResponseCodeIsSuccessful();
        $I->seeCurrentRouteIs('app_terId', ['id' => 1]);
    }
    // tests
    public function tryToTest(ControllerTester $I)
    {
    }
}
