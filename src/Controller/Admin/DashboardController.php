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
use App\Entity\TypeOfPayment;
use App\Entity\Wallet;
use App\Entity\WebsiteWallet;
use App\Repository\BetRepository;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use App\Repository\WebsiteWalletRepository;
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
    private UserRepository $userRepository;
    private BetRepository $betRepository;
    private EventRepository $eventRepository;
    private WebsiteWalletRepository $websiteWalletRepository;

    public function __construct(
        UserRepository $userRepository,
        BetRepository $betRepository,
        EventRepository $eventRepository,
        WebsiteWalletRepository $websiteWalletRepository
    ) {
        $this->userRepository = $userRepository;
        $this->betRepository = $betRepository;
        $this->eventRepository = $eventRepository;
        $this->websiteWalletRepository = $websiteWalletRepository;
    }
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $listOfUser = $this->userRepository->findAll();
        $listOfBet = $this->betRepository->findAll();
        $listOfEvent = $this->eventRepository->findAll();
        $websiteWallet = $this->websiteWalletRepository->findAll();

        // redirect to some CRUD controller
        $adminUrlGenerator  = $this->get(AdminUrlGenerator::class);
        $userUrl = $adminUrlGenerator->setController(UserCrudController::class)->generateUrl();
        $betUrl = $adminUrlGenerator->setController(BetCrudController::class)->generateUrl();
        $eventUrl = $adminUrlGenerator->setController(EventCrudController::class)->generateUrl();
        $websiteWalletUrl = $adminUrlGenerator->setController(WebsiteWalletCrudController::class)->generateUrl();

        return $this->render('bundles/EasyAdminBundle/welcome.html.twig', [

        'countAllUser' => count($listOfUser) ,
        'countAllBet' => count($listOfBet) ,
        'countAllEvent' => count($listOfEvent) ,
        'wallet' => $websiteWallet[0],
        'userUrl' => $userUrl,
        'betUrl' => $betUrl,
        'eventUrl' => $eventUrl,
        'websiteWalletUrl' => $websiteWalletUrl,

        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Online Bet');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Favourites', 'fas fa-star');
        yield MenuItem::linkToCrud('Bet', 'fas fa-list', Bet::class);
        yield MenuItem::linkToCrud('Event', 'fas fa-list', Event::class);
        yield MenuItem::linkToCrud('User', 'fas fa-list', User::class);

        yield MenuItem::section('All Items', 'fas fa-sitemap');

        yield MenuItem::subMenu('User Section', 'fas fa-list')->setSubItems([
            MenuItem::linkToCrud('User', 'fas fa-list', User::class),
            MenuItem::linkToCrud('Wallet', 'fas fa-list', Wallet::class),
            MenuItem::linkToCrud('BankAccount', 'fas fa-list', BankAccount::class),
            MenuItem::linkToCrud('BankAccount File', 'fas fa-list', BankAccountFile::class),
            MenuItem::linkToCrud('CardId File', 'fas fa-list', CardIdFile::class),
        ]);

        yield MenuItem::subMenu('Bet Section', 'fas fa-list')->setSubItems([
            MenuItem::linkToCrud('Bet', 'fas fa-list', Bet::class),
            MenuItem::linkToCrud('Type Of Bet', 'fas fa-list', TypeOfBet::class),
        ]);

        yield MenuItem::subMenu('Sport Section', 'fas fa-list')->setSubItems([
            MenuItem::linkToCrud('Event', 'fas fa-list', Event::class),
            MenuItem::linkToCrud('Competition', 'fas fa-list', Competition::class),
            MenuItem::linkToCrud('Team', 'fas fa-list', Team::class),
            MenuItem::linkToCrud('Player', 'fas fa-list', Player::class),
        ]);

        yield MenuItem::subMenu('Payment Section', 'fas fa-list')->setSubItems([
            MenuItem::linkToCrud('Website Wallet', 'fas fa-list', WebsiteWallet::class),
            MenuItem::linkToCrud('Payment', 'fas fa-list', Payment::class),
            MenuItem::linkToCrud('Type Of Payment', 'fas fa-list', TypeOfPayment::class),

        ]);

      //  yield MenuItem::linkToLogout('Logout', 'fa fa-exit');
    }
}
