<?php

namespace App\Controller;

use App\Repository\BetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BetController extends AbstractController
{

    public function index(BetRepository $betRepository): Response
    {
        $listOfBet = $betRepository->findAll();

        return $this->render('bet/_home_bet.html.twig', [
            'listOfBet' => $listOfBet,
        ]);
    }
}
