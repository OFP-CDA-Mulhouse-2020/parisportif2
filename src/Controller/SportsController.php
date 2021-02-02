<?php

namespace App\Controller;

use App\Entity\Sport;
use App\Repository\BetRepository;
use App\Repository\CompetitionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Bet;

class SportsController extends AbstractController
{
    /**
     * @Route("/app/sports/{sports}", name="app_sports")
     * @param string $sports
     * @param BetRepository $betRepository
     * @param CompetitionRepository $competitionRepository
     * @return Response
     */
    public function index(
        string $sports,
        BetRepository $betRepository,
        CompetitionRepository $competitionRepository
    ): Response {
        $listOfBet = $betRepository->findBetBySport($sports);
        $listOfCompetition = $competitionRepository->findCompetitionBySport($sports);
        //dd($findBetBySport);
        //dd($listOfCompetition);
        return $this->render('sports/index.html.twig', [
            'controller_name' => 'SportsController',
            'listOfBet' => $listOfBet,
            'listOfCompetition' => $listOfCompetition,
        ]);
    }
}
