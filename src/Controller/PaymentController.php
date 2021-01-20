<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Repository\PaymentRepository;
use App\Repository\TypeOfPaymentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $cart = $user->getCart();
        $wallet = $user->getWallet();
        $cart->setSum();
        $sum = $cart->getSum();

        $typeOfPayment = $typeOfPaymentRepository->findOneBy(
            [
                'typeOfPayment' => 'Internal Transfer Bet Payment'
            ]
        );

        $payment = new Payment($sum);
        $payment->setWallet($wallet);
        $payment->setItems($cart->getItems());
        $payment->setPaymentName('Ticket de test');
        $payment->setTypeOfPayment($typeOfPayment);

        //TODO : add website wallet

        $sumOfLastPayment = $paymentRepository->findAmountOfLastWeek($wallet->getId(), $typeOfPayment->getId());
        $walletStatus = $wallet->betPayment($sum, $sumOfLastPayment['amountOfLastWeek']);

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
}
