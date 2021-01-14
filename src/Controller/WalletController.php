<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Form\AddMoneyType;
use App\Form\WalletType;
use App\Form\WithdrawMoneyType;
use App\Repository\PaymentRepository;
use App\Repository\TypeOfPaymentRepository;
use App\Repository\WalletRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WalletController extends AbstractController
{
    /**
     * @Route("/app/wallet/balance", name="app_wallet_balance")
     */
    public function getWalletBalance(WalletRepository $walletRepository, PaymentRepository $paymentRepository): Response
    {
        $user = $this->getUser();
        $wallet = $walletRepository->find($user->getWallet()->getId());
        $payments = $paymentRepository->findBy(
            ['wallet' => $user->getWallet()->getId(), 'paymentStatusId' => 2],
            ['datePayment' => 'ASC']
        );

        return $this->render('wallet/balance.html.twig', [
            'wallet' => $wallet,
            'payments' => $payments
        ]);
    }

    /**
     * @Route("/app/wallet/add-money", name="app_wallet_add-money")
     */
    public function addMoneyToWallet(
        Request $request,
        WalletRepository $walletRepository,
        TypeOfPaymentRepository $typeOfPaymentRepository
    ): Response {
        $user = $this->getUser();
        $wallet = $walletRepository->find($user->getWallet()->getId());
        $formAddMoney = $this->createForm(AddMoneyType::class);
        $formAddMoney->handleRequest($request);

        if ($formAddMoney->isSubmitted() && $formAddMoney->isValid()) {
            $sum = $request->request->get('add_money')['amount'];
            $paymentStatus = $wallet->addMoney($sum);

            if ($paymentStatus) {
                $payment = new Payment($sum);
                $typeOfPayment = $typeOfPaymentRepository->findOneBy(
                    [
                        'typeOfPayment' => 'External Transfer Add Money To Wallet'
                    ]
                );

                $payment->setPaymentName('Ajout de fonds')
                    ->setWallet($wallet)
                    ->setTypeOfPayment($typeOfPayment)
                    ->acceptPayment();

                $entityManager = $this->getDoctrine()->getManager();

                $entityManager->persist($payment);
                $entityManager->flush();

                $this->addFlash('success', 'Votre versement a été réalisé avec succès !');
            } else {
                $formAddMoney->addError(new FormError('La transaction a échouée'));
            }
        }
        return $this->render('wallet/add-money.html.twig', [
            'wallet' => $wallet,
            'formAddMoney' => $formAddMoney->createView()
        ]);
    }

    /**
     * @Route("/app/wallet/withdraw-money", name="app_wallet_withdraw-money")
     */
    public function withdrawMoneyFromWallet(
        Request $request,
        WalletRepository $walletRepository,
        TypeOfPaymentRepository $typeOfPaymentRepository
    ): Response {
        $user = $this->getUser();
        $wallet = $walletRepository->find($user->getWallet()->getId());
        $formWithdrawMoney = $this->createForm(WithdrawMoneyType::class);
        $formWithdrawMoney->handleRequest($request);

        if ($formWithdrawMoney->isSubmitted() && $formWithdrawMoney->isValid()) {
            $sum = $request->request->get('withdraw_money')['amount'];
            $paymentStatus = $wallet->withdrawMoney($sum);

            if ($paymentStatus) {
                $payment = new Payment($sum);
                $typeOfPayment = $typeOfPaymentRepository->findOneBy(
                    [
                        'typeOfPayment' => 'External Transfer Withdraw Money From Wallet'
                    ]
                );

                $payment->setPaymentName('Retrait de fonds')
                ->setWallet($wallet)
                ->setTypeOfPayment($typeOfPayment)
                ->acceptPayment();

                $entityManager = $this->getDoctrine()->getManager();

                $entityManager->persist($payment);
                $entityManager->flush();

                $this->addFlash('success', 'Votre versement a été réalisé avec succès !');
            } else {
                $formWithdrawMoney->addError(new FormError('Montant supérieur au solde disponible'));
            }
        }

        return $this->render('wallet/withdraw-money.html.twig', [
            'wallet' => $wallet,
            'formWithdrawMoney' => $formWithdrawMoney->createView()
        ]);
    }

    /**
     * @Route("/app/wallet/limit-amount", name="app_wallet_limit-amount")
     */
    public function getLimitAmountPerWeekOfWallet(WalletRepository $walletRepository): Response
    {
        $user = $this->getUser();
        $wallet = $walletRepository->find($user->getWallet()->getId());
        $formWallet = $this->createForm(WalletType::class, $wallet);

        return $this->render('wallet/limit-amount.html.twig', [
            'wallet' => $wallet,
            'editLimitAmount' => false,
            'formWallet' => $formWallet->createView()
        ]);
    }

    /**
     * @Route("/app/wallet/limit-amount/edit", name="app_wallet_limit-amount_edit")
     */
    public function setLimitAmountPerWeekOfWallet(
        Request $request,
        WalletRepository $walletRepository
    ): Response {
        $user = $this->getUser();
        $wallet = $walletRepository->find($user->getWallet()->getId());
        $formWallet = $this->createForm(WalletType::class, $wallet);
        $formWallet->handleRequest($request);

        if ($formWallet->isSubmitted() && $formWallet->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($wallet);
            $entityManager->flush();

            $this->addFlash('success', 'Votre limite de jeu a bien été changé !');

            return $this->redirectToRoute('app_wallet_limit-amount');
        }
        return $this->render('wallet/limit-amount.html.twig', [
            'wallet' => $wallet,
            'editLimitAmount' => true,
            'formWallet' => $formWallet->createView()
        ]);
    }
}
