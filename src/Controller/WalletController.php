<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Wallet;
use App\Form\AddMoneyType;
use App\Form\WalletType;
use App\Form\WithdrawMoneyType;
use App\FormHandler\AddMoneyHandler;
use App\FormHandler\LimitAmountWalletHandler;
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
    public function getWalletBalance(PaymentRepository $paymentRepository): Response
    {
        $user = $this->getUser();
        assert($user instanceof User);
        $wallet = $user->getWallet();
        assert($wallet instanceof Wallet);

        $payments = $paymentRepository->findBy(
            ['wallet' => $wallet->getId(), 'paymentStatusId' => 2],
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
        AddMoneyHandler $addedMoneyHandler
    ): Response {
        $user = $this->getUser();
        assert($user instanceof User);
        $wallet = $user->getWallet();
        assert($wallet instanceof Wallet);

        $addMoneyForm = $this->createForm(AddMoneyType::class);
        $addMoneyForm->handleRequest($request);

        if ($addMoneyForm->isSubmitted() && $addMoneyForm->isValid()) {
            try {
                $addedMoneyHandler->process($addMoneyForm, $user);
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
        WithdrawMoneyHandler $withdrawalMoneyHandler
    ): Response {
        $user = $this->getUser();
        assert($user instanceof User);
        $wallet = $user->getWallet();
        assert($wallet instanceof Wallet);

        $withdrawMoneyForm = $this->createForm(WithdrawMoneyType::class);
        $withdrawMoneyForm->handleRequest($request);

        if ($withdrawMoneyForm->isSubmitted() && $withdrawMoneyForm->isValid()) {
            try {
                $withdrawalMoneyHandler->process($withdrawMoneyForm, $user);
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
     * @param Request $request
     * @param LimitAmountWalletHandler $limitAmountWalletHandler
     * @return Response
     */
    public function getLimitAmountPerWeekOfWallet(
        Request $request,
        LimitAmountWalletHandler $limitAmountWalletHandler
    ): Response {
        $user = $this->getUser();
        assert($user instanceof User);
        $wallet = $user->getWallet();
        assert($wallet instanceof Wallet);
        $walletForm = $this->createForm(WalletType::class, $wallet);
        $walletForm->handleRequest($request);
        if ($walletForm->isSubmitted() && $walletForm->isValid()) {
            $limitAmountWalletHandler->process($walletForm);
            $this->addFlash('success', 'Votre limite de jeu a bien été changé !');
            return $this->redirectToRoute('app_wallet_limit-amount');
        }
        return $this->render('wallet/limit-amount.html.twig', [
            'wallet' => $wallet,
            'walletForm' => $walletForm->createView()
        ]);
    }
}
