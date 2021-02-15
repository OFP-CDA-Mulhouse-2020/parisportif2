<?php

namespace App\Controller\Admin;

use App\Entity\Bet;
use App\Entity\Competition;
use App\Entity\Event;
use App\Entity\Player;
use App\Entity\Team;
use App\Entity\TypeOfBet;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        // redirect to some CRUD controller
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Html');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Bet', 'fas fa-list', Bet::class);
        yield MenuItem::linkToCrud('Event', 'fas fa-list', Event::class);
        yield MenuItem::linkToCrud('TypeOfBet', 'fas fa-list', TypeOfBet::class);
        yield MenuItem::linkToCrud('Player', 'fas fa-list', Player::class);
        yield MenuItem::linkToCrud('Team', 'fas fa-list', Team::class);
        yield MenuItem::linkToCrud('Competition', 'fas fa-list', Competition::class);
        yield MenuItem::linkToCrud('User', 'fas fa-list', User::class);
    }
}
