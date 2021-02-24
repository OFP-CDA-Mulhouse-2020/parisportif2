<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\User;
use App\Repository\BetRepository;
use App\Repository\PaymentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/app", name="app")
     * @Route("/app/event/{typeOfSport}/{eventId}", name="app_event")
     * @Route("/app/sport/{typeOfSport}", name="app_sport")
     */
    public function homePage(): Response
    {
        return $this->render('home/home.html.twig', []);
    }
}
