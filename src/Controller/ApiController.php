<?php

namespace App\Controller;

use App\Entity\Bet;
use App\Entity\Cart;
use App\Entity\Item;
use App\Entity\User;
use App\Repository\BetRepository;
use App\Repository\CompetitionRepository;
use App\Repository\EventRepository;
use App\Repository\ItemRepository;
use App\Repository\SportRepository;
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
    public function showHomeBet(BetRepository $betRepository): Response
    {
        $listOfBet = $betRepository->findAllSimpleBet();

        return $this->json(["listOfBet" => $listOfBet, "cart" => $this->showCart()]);
    }


    private function showCart(): ?Cart
    {
        $user = $this->getUser();
        assert($user instanceof User);

        /** @var Cart|null $cart */
        $cart = $user->getCart();

        return $cart;
    }

    /**
     * @Route("/api/sport/{typeOfSport}", name="api_sport")
     */
    public function showBetsBySport(
        string $typeOfSport,
        BetRepository $betRepository,
        CompetitionRepository $competitionRepository,
        SportRepository $sportRepository
    ): Response {

        $listOfBet = $betRepository->findSimpleBetBySport($typeOfSport);
        $listOfCompetition = $competitionRepository->findCompetitionBySport($typeOfSport);
        $sport = $sportRepository->findOneBy(['name' => $typeOfSport]);

        return $this->json([
            "listOfBet" => $listOfBet,
            "sport" => $sport,
            "cart" => $this->showCart()
        ]);
    }

    /**
     * @Route("/api/event/{typeOfSport}/{eventId}", name="api_event")
     */
    public function showBetsByEvent(
        string $typeOfSport,
        int $eventId,
        EventRepository $eventRepository,
        BetRepository $betRepository,
        SportRepository $sportRepository,
        CompetitionRepository $competitionRepository
    ): Response {

        $event = $eventRepository->find($eventId);
        $listOfBet = $betRepository->findBy(['event' => $eventId, 'betOpened' => true]);
        $sport = $sportRepository->findOneBy(['name' => $typeOfSport]);
        $listOfCompetition = $competitionRepository->findCompetitionBySport($typeOfSport);

        return $this->json([
            "listOfBet" => $listOfBet,
            "event" => $event,
            "sport" => $sport,
            "cart" => $this->showCart()
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


        return $this->json($this->showCart());
    }

    /**
     * @Route("api/cart/remove/{itemId}", name="api_cart_remove_item")
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

        return $this->json($this->showCart());
    }

    /**
     * @Route("api/cart/changeBetAmount/{itemId}", name="api_cart_change_bet_amount")
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

        $newAmount = (int) $request->getContent();

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

        return $this->json($this->showCart());
    }
}
