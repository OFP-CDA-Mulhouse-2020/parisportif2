<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\User;
use App\Repository\BetRepository;
use App\Repository\CompetitionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SportsController extends AbstractController
{
    /**
     * @Route("/app/sports/{typeOfSport}", name="app_sports")
     * @param string $typeOfSport
     * @param BetRepository $betRepository
     * @param CompetitionRepository $competitionRepository
     * @return Response
     */
    public function index(
        string $typeOfSport,
        BetRepository $betRepository,
        CompetitionRepository $competitionRepository
    ): Response {

        $listOfBet = $betRepository->findBetBySport($typeOfSport);
        $listOfCompetition = $competitionRepository->findCompetitionBySport($typeOfSport);

        $user = $this->getUser();
        assert($user instanceof User);

        /** @var Cart|null $cart */
        $cart = $user->getCart();

        if ($cart) {
            $items = $cart->getItems();
        } else {
            $items = null;
        }

        return $this->render('sports/sports.html.twig', [
            'controller_name' => 'SportsController',
            'listOfBet' => $listOfBet,
            'listOfCompetition' => $listOfCompetition,
            'cart' => $cart,
            'items' => $items,
        ]);
    }
}
