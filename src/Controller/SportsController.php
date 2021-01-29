<?php

namespace App\Controller;

use App\Entity\Sport;
use App\Repository\BetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Bet;

class SportsController extends AbstractController
{
    /**
     * @Route("/app/sports/{sports}", name="app_sports")
     */
    public function index(string $sports, BetRepository $betRepository): Response
    {
        $listOfBet = $betRepository->findBetBySport($sports);
        //dd($findBetBySport);
        return $this->render('sports/index.html.twig', [
            'controller_name' => 'SportsController',
            'listOfBet' => $listOfBet
        ]);
    }
}
