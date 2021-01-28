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
     * @param $sports
     * @param BetRepository $betRepository
     * @return Response
     */
    public function index(int $sports, BetRepository $betRepository): Response
    {
        $sports = new Sport();
        //dd($sports);
        //$listOfBet = $betRepository->findby(['betOpened' => true]);
        //$listOfBet = $betRepository->find([2 => true]);
        $listOfBet = $betRepository->find($sports = true);
        dd($listOfBet);
        //dd($sports);
        return $this->render('sports/index.html.twig', [
            'controller_name' => 'SportsController',
        ]);
    }
}
