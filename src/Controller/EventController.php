<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\User;
use App\Repository\BetRepository;
use App\Repository\CompetitionRepository;
use App\Repository\EventRepository;
use App\Repository\SportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    /**
     * @Route("/app/event/{typeOfSport}/{eventId}", name="app_event")
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
        $listOfBet = $betRepository->findBy(['event' => $eventId]);
        $sport = $sportRepository->findOneBy(['name' => $typeOfSport]);
        $listOfCompetition = $competitionRepository->findCompetitionBySport($typeOfSport);

        $user = $this->getUser();
        assert($user instanceof User);

        /** @var Cart|null $cart */
        $cart = $user->getCart();

        if ($cart) {
            $items = $cart->getItems();
        } else {
            $items = null;
        }

        return $this->render('event/event.html.twig', [
            'event' => $event,
            'listOfBet' => $listOfBet,
            'listOfCompetition' => $listOfCompetition,
            'sport' => $sport,
            'cart' => $cart,
            'items' => $items,
        ]);
    }
}
