<?php


namespace App\Tests\Controller;

use App\Factory\CalendrierFactory;
use App\Factory\ClasseFactory;
use App\Factory\UtilisateurFactory;
use App\Tests\Support\ControllerTester;
use Codeception\Util\HttpCode;

class UtilisateurCest {

    public function utilisateurRestricted(ControllerTester $I): void {
        $I->amOnPage('/utilisateur');
        $I->seeCurrentRouteIs('app_login');
    }

    public function loggedOnly(ControllerTester $I): void {
        ClasseFactory::createOne();
        CalendrierFactory::createOne();
        $post = UtilisateurFactory::createOne();
        $realPost = $post->object();
        $I->amLoggedInAs($realPost);
        $I->amOnPage('/utilisateur');
        $I->seeCurrentRouteIs('app_utilisateur');
    }

    public function cvRestricted(ControllerTester $I): void {
        ClasseFactory::createOne();
        CalendrierFactory::createOne();
        $post = UtilisateurFactory::createOne([
            'roles' => ['ROLE_PROFESSEUR'],
        ]);
        $realPost = $post->object();
        $I->amLoggedInAs($realPost);
        $I->amOnPage('/utilisateur/1/cv');
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function cvRestrictedForStudent(ControllerTester $I): void
    {
        ClasseFactory::createOne();
        CalendrierFactory::createOne();
        $post = UtilisateurFactory::createOne([
            'roles' => ['ROLE_ETUDIANT'],
        ]);
        $realPost = $post->object();
        $I->amLoggedInAs($realPost);
        $I->amOnPage('/utilisateur/1/cv');
        $I->seeCurrentRouteIs('app_utilisateur_show');
    }
}