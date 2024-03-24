<?php

namespace App\Tests\Controller\Ter;

use App\Factory\TerFactory;
use App\Tests\Support\ControllerTester;

class DeleteCest
{
    public function form(ControllerTester $I): void
    {
        TerFactory::createOne(['nomsujet'=> 'machin truc']);

        $I->amOnPage('/ter/1/delete');

        $I->seeInTitle('Suppression de machin truc');
        $I->see('Suppression de machin truc', 'h1');
    }
}
