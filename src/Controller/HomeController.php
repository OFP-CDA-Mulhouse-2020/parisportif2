<?php

namespace App\Controller;

use App\Repository\BetRepository;
use App\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/app", name="app")
     */
    public function homePage(BetRepository $betRepository): Response
    {

        $listOfBet = $betRepository->findAll();

        $user = $this->getUser();
        $cart = $user->getCart();
        if ($cart) {
            $items = $cart->getItems();
        } else {
            $items = null;
        }

        return $this->render('home/home.html.twig', [
            'listOfBet' => $listOfBet,
            'cart' => $cart,
            'items' => $items,

        ]);
    }
}
