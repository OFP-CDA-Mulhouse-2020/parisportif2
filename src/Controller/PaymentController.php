<?php

namespace App\Controller;

use App\Entity\Bet;
use App\Entity\Cart;
use App\Entity\Payment;
use App\Entity\TypeOfPayment;
use App\Entity\User;
use App\Entity\Wallet;
use App\Factory\PaymentFactory;
use App\Repository\BetRepository;
use App\Repository\ItemRepository;
use App\Repository\PaymentRepository;
use App\Repository\TypeOfPaymentRepository;
use App\Repository\WebsiteWalletRepository;
use App\Service\GenerateBetPaymentService;
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
        PaymentRepository $paymentRepository,
        WebsiteWalletRepository $websiteWalletRepository
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
        $websiteWallet = $websiteWalletRepository->findAll()[0];

        $payment = PaymentFactory::makePaymentForBetPayment($sum, $wallet, $websiteWallet, $cart, $typeOfPayment);

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
            $websiteWallet->addToBalance($sum);

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
