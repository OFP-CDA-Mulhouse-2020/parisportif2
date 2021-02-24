<?php

namespace App\Controller;

use App\Entity\Bet;
use App\Entity\Cart;
use App\Entity\Item;
use App\Entity\User;
use App\Entity\Wallet;
use App\Factory\ItemFactory;
use App\Repository\BetRepository;
use App\Repository\ItemRepository;
use App\Service\DatabaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("app/cart/history", name="app_cart_history")
     */
    public function displayCartHistory(ItemRepository $itemRepository): Response
    {
        $user = $this->getUser();
        assert($user instanceof User);
        $wallet = $user->getWallet();
        assert($wallet instanceof Wallet);

        $listOfItems = $itemRepository->findAllItemsByUserWallet($wallet);

        return $this->render('cart/cart_history.html.twig', [
            'listOfItems' => $listOfItems
        ]);
    }
}
