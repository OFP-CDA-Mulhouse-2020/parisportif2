<?php

namespace App\Controller;

use App\Entity\Bet;
use App\Entity\Cart;
use App\Entity\Payment;
use App\Entity\TypeOfPayment;
use App\Entity\User;
use App\Entity\Wallet;
use App\Repository\BetRepository;
use App\Repository\ItemRepository;
use App\Repository\PaymentRepository;
use App\Repository\TypeOfPaymentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    /**
     * @Route("app/cart/payment", name="app_cart_payment")
     */
    public function validateCartToPayment(
        TypeOfPaymentRepository $typeOfPaymentRepository,
        PaymentRepository $paymentRepository
    ): Response {
        $user = $this->getUser();
        assert($user instanceof User);
        $cart = $user->getCart();
        assert($cart instanceof Cart);
        $wallet = $user->getWallet();
        assert($wallet instanceof Wallet);

        $cart->setSum();
        /** @var float $sum */
        $sum = $cart->getSum();

        $typeOfPayment = $typeOfPaymentRepository->findOneBy(
            [
                'typeOfPayment' => 'Internal Transfer Bet Payment'
            ]
        );
        assert($typeOfPayment instanceof TypeOfPayment);

        $payment = new Payment($sum);
        $payment->setWallet($wallet);
        $payment->setItems($cart->getItems());
        $payment->setPaymentName('Ticket de test');
        $payment->setTypeOfPayment($typeOfPayment);

        //TODO : add website wallet

        /** @var array $sumOfLastWeekPayment */
        $sumOfLastWeekPayment = $paymentRepository->findAmountOfLastWeek(
            $wallet->getId(),
            (int) $typeOfPayment->getId()
        );

        $walletStatus = $wallet->betPayment($sum, $sumOfLastWeekPayment['amountOfLastWeek']);

        if ($walletStatus === 0) {
            $this->addFlash('error', 'Limite de jeu hebdomadaire dépassée ! Misez moins ou patientez un petit peu !');
            $payment->refusePayment();
        } elseif ($walletStatus === 1) {
            $this->addFlash('error', 'Fonds insuffisants ! Rajoutez des fonds et validez votre tickets');
            $payment->refusePayment();
        } else {
            $this->addFlash('success', 'Paiement effectué avec succès !');
            $entityManager = $this->getDoctrine()->getManager();

            $payment->acceptPayment();
            $items = $payment->getItems();

            foreach ($items as $item) {
                $item->payItem();
                $item->setCart(null);
                $item->setPayment($payment);
                $entityManager->persist($item);
            }

            $user->setCart(null);
            $entityManager->remove($cart);
            $entityManager->persist($user);
            $entityManager->persist($payment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app');
    }

    /**
     * @Route("app/cart/betpayment/{id}", name="app_cart_bet_payment")
     */
    public function validateBetToPayment(
        int $id,
        BetRepository $betRepository,
        ItemRepository $itemRepository,
        TypeOfPaymentRepository $typeOfPaymentRepository,
        Request $request
    ): Response {

        $bet = $betRepository->find($id);
        assert($bet instanceof Bet);

        if ($bet->isBetOpened()) {
            return new RedirectResponse($request->server->get('HTTP_REFERER'));
        }

        $user = $this->getUser();
        assert($user instanceof User);
        $wallet = $user->getWallet();
        assert($wallet instanceof Wallet);

        /** @var array $result */
        $result = $bet->getBetResult();

        $listOfItems = $itemRepository->findBy(['bet' => $bet->getId(), 'itemStatusId' => 1]);
        $entityManager = $this->getDoctrine()->getManager();

        foreach ($listOfItems as $item) {
            $expectedResult = $item->getExpectedBetResult();

            if (in_array($expectedResult, $result)) {
                $item->winItem();
            } else {
                $item->looseItem();
            }

                $sum = $item->calculateProfits();

            if ($sum !== null) {
                $typeOfPayment = $typeOfPaymentRepository->findOneBy(
                    [
                    'typeOfPayment' => 'Internal Transfer Bet Earning'
                    ]
                );
                assert($typeOfPayment instanceof TypeOfPayment);

                $payment = new Payment($sum);
                $payment->setWallet($wallet);
                $payment->setPaymentName('Gain sur ticket de pari n°');
                $payment->setTypeOfPayment($typeOfPayment);

                $walletStatus = $wallet->addMoney($sum);

                if (!$walletStatus) {
                    throw new \LogicException("Montant incorrect");
                }
                $payment->acceptPayment();

                $entityManager->persist($wallet);
                $entityManager->persist($payment);
            }

            $entityManager->persist($item);
            $entityManager->flush();
        }

        return new RedirectResponse($request->server->get('HTTP_REFERER'));
    }
}
