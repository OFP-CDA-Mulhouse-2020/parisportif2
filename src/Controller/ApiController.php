<?php

namespace App\Controller;

use App\Entity\Bet;
use App\Entity\Cart;
use App\Entity\Item;
use App\Entity\User;
use App\Repository\BetRepository;
use App\Repository\ItemRepository;
use App\Service\DatabaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/home", name="api_home")
     */
    public function homePage(): Response
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }


    /**
     * @Route("api/cart/add/{betId}/{expectedResult}", name="api_cart_add_item")
     */
    public function addItemToCart(int $betId, int $expectedResult, BetRepository $betRepository): Response
    {

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

        $odds = $bet->getListOfOdds()[$expectedResult];

        $item = new Item($bet);
        $item->setExpectedBetResult($expectedResult);
        $item->isModifiedRecordedOdds($odds[1]);
        $item->isModifiedAmount(5);

        $cart->addItem($item);
        $cart->setSum();

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($item);
        $entityManager->persist($cart);
        $entityManager->flush();


        return $response = JsonResponse::fromJsonString('{"validation" : "true"}');
    }

    /**
     * @Route("api/cart/remove/{itemId}", name="app_cart_remove_item")
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

        return $response = JsonResponse::fromJsonString('{"validation" : "true"}');
    }

    /**
     * @Route("api/cart/changeBetAmount/{itemId}", name="app_cart_change_bet_amount")
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

        return $response = JsonResponse::fromJsonString('{"validation" : "true"}');
    }
}
