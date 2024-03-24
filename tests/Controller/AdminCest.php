<?php


namespace App\Tests\Controller;

use App\Factory\CalendrierFactory;
use App\Factory\ClasseFactory;
use App\Factory\UtilisateurFactory;
use App\Tests\Support\ControllerTester;
use Codeception\Util\HttpCode;

class AdminCest
{
    public function adminOnly(ControllerTester $I): void
    {
        CalendrierFactory::createOne();
        ClasseFactory::createOne();
        $post = UtilisateurFactory::createOne([
            'roles' => ['ROLE_USER'],
        ]);
        $realPost = $post->object();
        $I->amLoggedInAs($realPost);
        $I->amOnPage('/admin');
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function adminRestricted(ControllerTester $I): void
    {
        CalendrierFactory::createOne();
        ClasseFactory::createOne();
        $post = UtilisateurFactory::createOne([
            'roles' => ['ROLE_ADMIN'],
        ]);
        $realPost = $post->object();
        $I->amLoggedInAs($realPost);
        $I->amOnPage('/admin');
        $I->seeCurrentRouteIs('admin');
    }

}
