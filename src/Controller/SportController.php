<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\User;
use App\Repository\BetRepository;
use App\Repository\CompetitionRepository;
use App\Repository\SportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SportController extends AbstractController
{
    /**
     * @Route("/app/sport/{typeOfSport}", name="app_sport")
     */
    public function showBetsBySport(
        string $typeOfSport,
        BetRepository $betRepository,
        CompetitionRepository $competitionRepository,
        SportRepository $sportRepository
    ): Response {

        $listOfBet = $betRepository->findBetBySport($typeOfSport);
        $listOfCompetition = $competitionRepository->findCompetitionBySport($typeOfSport);
        $sport = $sportRepository->findOneBy(['name' => $typeOfSport]);
        $user = $this->getUser();
        assert($user instanceof User);

        /** @var Cart|null $cart */
        $cart = $user->getCart();

        if ($cart) {
            $items = $cart->getItems();
        } else {
            $items = null;
        }

        return $this->render('sport/sport.html.twig', [
            'listOfBet' => $listOfBet,
            'listOfCompetition' => $listOfCompetition,
            'sport' => $sport,
            'cart' => $cart,
            'items' => $items,
        ]);
    }
}
