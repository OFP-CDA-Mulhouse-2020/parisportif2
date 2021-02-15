<?php

namespace App\Controller\Admin;

use App\Entity\BankAccount;
use App\Entity\BankAccountFile;
use App\Entity\Bet;
use App\Entity\CardIdFile;
use App\Entity\Competition;
use App\Entity\Event;
use App\Entity\Payment;
use App\Entity\Player;
use App\Entity\Team;
use App\Entity\TypeOfBet;
use App\Entity\Wallet;
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

        yield MenuItem::section('Favourites', 'fas fa-star');
        yield MenuItem::linkToCrud('Bet', 'fas fa-list', Bet::class);
        yield MenuItem::linkToCrud('Event', 'fas fa-list', Event::class);
        yield MenuItem::linkToCrud('User', 'fas fa-list', User::class);

        yield MenuItem::subMenu('User Section', 'fas fa-list')->setSubItems([
            MenuItem::linkToCrud('User', 'fas fa-list', User::class),
            MenuItem::linkToCrud('Wallet', 'fas fa-list', Wallet::class),
            MenuItem::linkToCrud('BankAccount', 'fas fa-list', BankAccount::class),
            MenuItem::linkToCrud('BankAccountFile', 'fas fa-list', BankAccountFile::class),
            MenuItem::linkToCrud('User CardIdFile', 'fas fa-list', CardIdFile::class),
        ]);

        yield MenuItem::subMenu('Bet Section', 'fas fa-list')->setSubItems([
            MenuItem::linkToCrud('Bet', 'fas fa-list', Bet::class),
            MenuItem::linkToCrud('TypeOfBet', 'fas fa-list', TypeOfBet::class),
        ]);

        yield MenuItem::subMenu('Sport Section', 'fas fa-list')->setSubItems([
            MenuItem::linkToCrud('Event', 'fas fa-list', Event::class),
            MenuItem::linkToCrud('Competition', 'fas fa-list', Competition::class),
            MenuItem::linkToCrud('Team', 'fas fa-list', Team::class),
            MenuItem::linkToCrud('Player', 'fas fa-list', Player::class),
        ]);

        yield MenuItem::subMenu('Payment Section', 'fas fa-list')->setSubItems([
            MenuItem::linkToCrud('Payment', 'fas fa-list', Payment::class),
        ]);
    }
}
