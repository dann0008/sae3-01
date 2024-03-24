<?php

namespace App\Controller\Admin;

use App\Entity\Calendrier;
use App\Entity\Classe;
use App\Entity\StageAlt;
use App\Entity\Ter;
use App\Entity\Utilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Masteria');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Calendrier','fas fa-tags',Calendrier::class);
        yield MenuItem::linkToCrud('Classes','fas fa-tags',Classe::class);
        yield MenuItem::linkToCrud('Stages/Alternances','fas fa-tags',StageAlt::class);
        yield MenuItem::linkToCrud('Ters','fas fa-tags',Ter::class);
        yield MenuItem::linkToCrud('Utilisateurs','fas fa-tags',Utilisateur::class);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
