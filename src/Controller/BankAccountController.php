<?php

namespace App\Controller;

use App\Form\BankAccountType;
use App\Repository\BankAccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BankAccountController extends AbstractController
{

    /**
     * @Route("/app/wallet/bank-account", name="app_wallet_bank-account")
     */
    public function getBankAccountInformations(
        BankAccountRepository $bankAccountRepository
    ): Response {
        $user = $this->getUser();
        $bankAccount = $bankAccountRepository->find($user->getBankAccount()->getId());
        $formBankAccount = $this->createForm(BankAccountType::class, $bankAccount);

        return $this->render('wallet/bank-account.html.twig', [
            'user' => $user,
            'editBankAccount' => false,
            'formBankAccount' => $formBankAccount->createView()
        ]);
    }

    /**
     * @Route("/app/wallet/bank-account/edit", name="app_wallet_bank-account_edit")
     */
    public function setBankAccountInformations(
        Request $request,
        BankAccountRepository $bankAccountRepository
    ): Response {
        $user = $this->getUser();
        $bankAccount = $bankAccountRepository->find($user->getBankAccount()->getId());
        $formBankAccount = $this->createForm(BankAccountType::class);
        $formBankAccount->handleRequest($request);

        if ($formBankAccount->isSubmitted() && $formBankAccount->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($bankAccount);
            $entityManager->flush();

            $this->addFlash('success', 'Vos coordonnées bancaires ont été mises à jour !');

            return $this->redirectToRoute('app_wallet_bank-account');
        }
        return $this->render('wallet/bank-account.html.twig', [
            'user' => $user,
            'editBankAccount' => true,
            'formBankAccount' => $formBankAccount->createView()
        ]);
    }
}
