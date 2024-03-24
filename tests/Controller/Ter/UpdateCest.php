<?php

namespace App\Tests\Controller\Ter;

use App\Factory\TerFactory;
use App\Tests\Support\ControllerTester;

class UpdateCest
{
    public function form(ControllerTester $I): void
    {
        TerFactory::createOne([
            'nomsujet'=> 'machin truc',
        ]);

        $I->amOnPage('/ter/1/update');

        $I->seeInTitle('Édition de machin truc');
        $I->see('Édition de machin truc', 'h1');
    }
}
