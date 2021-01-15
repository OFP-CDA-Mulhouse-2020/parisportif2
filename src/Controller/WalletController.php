<?php

namespace App\Controller;

use App\Form\AddMoneyType;
use App\Form\WalletType;
use App\Form\WithdrawMoneyType;
use App\FormHandler\AddMoneyHandler;
use App\FormHandler\WalletHandler;
use App\FormHandler\WithdrawMoneyHandler;
use App\Repository\PaymentRepository;
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
        AddMoneyHandler $addMoneyHandler
    ): Response {
        $user = $this->getUser();
        $wallet = $user->getWallet();
        $addMoneyForm = $this->createForm(AddMoneyType::class);
        $addMoneyForm->handleRequest($request);

        if ($addMoneyForm->isSubmitted() && $addMoneyForm->isValid()) {
            try {
                $addMoneyHandler->process($addMoneyForm, $user);
                $this->addFlash('success', 'Votre versement a été réalisé avec succès !');
            } catch (\LogicException $e) {
                $addMoneyForm->addError(new FormError('La transaction a échouée'));
            }
        }
        return $this->render('wallet/add-money.html.twig', [
            'wallet' => $wallet,
            'addMoneyForm' => $addMoneyForm->createView()
        ]);
    }

    /**
     * @Route("/app/wallet/withdraw-money", name="app_wallet_withdraw-money")
     */
    public function withdrawMoneyFromWallet(
        Request $request,
        WithdrawMoneyHandler $withdrawMoneyHandler
    ): Response {
        $user = $this->getUser();
        $wallet = $user->getWallet();
        $withdrawMoneyForm = $this->createForm(WithdrawMoneyType::class);
        $withdrawMoneyForm->handleRequest($request);

        if ($withdrawMoneyForm->isSubmitted() && $withdrawMoneyForm->isValid()) {
            try {
                $withdrawMoneyHandler->process($withdrawMoneyForm, $user);
                $this->addFlash('success', 'Votre versement a été réalisé avec succès !');
            } catch (\LogicException $e) {
                $withdrawMoneyForm->addError(new FormError('Montant supérieur au solde disponible'));
            }
        }

        return $this->render('wallet/withdraw-money.html.twig', [
            'wallet' => $wallet,
            'withdrawMoneyForm' => $withdrawMoneyForm->createView()
        ]);
    }

    /**
     * @Route("/app/wallet/limit-amount", name="app_wallet_limit-amount")
     */
    public function getLimitAmountPerWeekOfWallet(WalletRepository $walletRepository): Response
    {
        $user = $this->getUser();
        $wallet = $walletRepository->find($user->getWallet()->getId());
        $walletForm = $this->createForm(WalletType::class, $wallet);

        return $this->render('wallet/limit-amount.html.twig', [
            'wallet' => $wallet,
            'editLimitAmount' => false,
            'walletForm' => $walletForm->createView()
        ]);
    }

    /**
     * @Route("/app/wallet/limit-amount/edit", name="app_wallet_limit-amount_edit")
     */
    public function setLimitAmountPerWeekOfWallet(
        Request $request,
        WalletHandler $walletHandler
    ): Response {
        $user = $this->getUser();
        $wallet = $user->getWallet();
        $walletForm = $this->createForm(WalletType::class, $wallet);
        $walletForm->handleRequest($request);

        if ($walletForm->isSubmitted() && $walletForm->isValid()) {
            $walletHandler->process($walletForm);
            $this->addFlash('success', 'Votre limite de jeu a bien été changé !');

            return $this->redirectToRoute('app_wallet_limit-amount');
        }
        return $this->render('wallet/limit-amount.html.twig', [
            'wallet' => $wallet,
            'editLimitAmount' => true,
            'walletForm' => $walletForm->createView()
        ]);
    }
}
