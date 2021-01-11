<?php

namespace App\Controller;

use App\Form\BankAccountType;
use App\Form\WalletType;
use App\Repository\WalletRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WalletController extends AbstractController
{
    /**
     * @Route("/app/wallet/balance", name="app_wallet_balance")
     */
    public function getWalletBalance(WalletRepository $walletRepository): Response
    {
        $user = $this->getUser();
        $wallet = $walletRepository->find($user->getWallet()->getId());

        return $this->render('wallet/balance.html.twig', [
            'wallet' => $wallet,

        ]);
    }

    /**
     * @Route("/app/wallet/add-money", name="app_wallet_add-money")
     */
    public function addMoneyToWallet(WalletRepository $walletRepository): Response
    {
        $user = $this->getUser();
        $wallet = $walletRepository->find($user->getWallet()->getId());

        return $this->render('wallet/add-money.html.twig', [
            'wallet' => $wallet,

        ]);
    }

    /**
     * @Route("/app/wallet/withdraw-money", name="app_wallet_withdraw-money")
     */
    public function withdrawMoneyfromWallet(WalletRepository $walletRepository): Response
    {
        $user = $this->getUser();
        $wallet = $walletRepository->find($user->getWallet()->getId());

        return $this->render('wallet/withdraw-money.html.twig', [
            'wallet' => $wallet,

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
