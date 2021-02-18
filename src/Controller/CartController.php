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
     * @Route("app/cart/add/{betId}/{expectedResult}", name="app_cart_add_item")
     */
    public function addItemToCart(
        int $betId,
        int $expectedResult,
        BetRepository $betRepository,
        DatabaseService $databaseService
    ): Response {
        $user = $this->getUser();
        assert($user instanceof User);

        if ($user->getCart() === null) {
            $cart = new Cart();
            $user->setCart($cart);
        }

        $cart = $user->getCart();
        assert($cart instanceof Cart);

        $bet = $betRepository->find($betId);
        assert($bet instanceof Bet);

        $item = ItemFactory::makeItem($bet, $expectedResult);

        $cart->addItem($item);
        $cart->setSum();

        $databaseService->saveToDatabase($item);
        $databaseService->saveToDatabase($cart);

        return $this->redirectToRoute('app');
    }

    /**
     * @Route("app/cart/remove/{itemId}", name="app_cart_remove_item")
     */
    public function removeItemFromCart(int $itemId, ItemRepository $itemRepository): Response
    {
        $user = $this->getUser();
        assert($user instanceof User);
        $cart = $user->getCart();
        assert($cart instanceof Cart);

        $item = $itemRepository->find($itemId);
        assert($item instanceof Item);

        $cart->removeItem($item);
        $cart->setSum();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($item);

        if (count($cart->getItems()) === 0) {
            $user->setCart(null);
            $entityManager->remove($cart);
            $entityManager->persist($user);
        } else {
            $entityManager->persist($cart);
        }
        $entityManager->flush();

        return $this->redirectToRoute('app');
    }

    /**
     * @Route("app/cart/changeBetAmount/{itemId}", name="app_cart_change_bet_amount")
     */
    public function changeBetAmount(
        Request $request,
        ItemRepository $itemRepository,
        DatabaseService $databaseService,
        int $itemId
    ): Response {
        $user = $this->getUser();
        assert($user instanceof User);
        $cart = $user->getCart();
        assert($cart instanceof Cart);

        $newAmount = $request->request->get("change_amount");

        // Récupération du pari (objet)
        $item = $itemRepository->find($itemId);
        assert($item instanceof Item);

        //modification de la mise
        $itemStatus = $item->isModifiedAmount($newAmount);
        //calcul du total du panier
        $cart->setSum();

        if (!$itemStatus) {
            $this->addFlash('error', 'Le montant est incorrect !');
        }
        //Enregistrement en base de données
        $databaseService->saveToDatabase($cart);

        return $this->redirectToRoute('app');
    }


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
