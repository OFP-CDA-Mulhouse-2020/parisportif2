<?php

namespace App\Controller;

use App\Form\BankAccountType;
use App\FormHandler\BankAccountHandler;
use App\Repository\BankAccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BankAccountController extends AbstractController
{

    /**
     * @Route("/app/wallet/bank-account", name="app_wallet_bank-account")
     * @param BankAccountRepository $bankAccountRepository
     * @return Response
     */
    public function getBankAccountInformations(
        BankAccountRepository $bankAccountRepository
    ): Response {
        $user = $this->getUser();
        $bankAccount = $user->getBankAccount();
        $bankAccountForm = $this->createForm(BankAccountType::class, $bankAccount);

        return $this->render('wallet/bank-account.html.twig', [
            'user' => $user,
            'editBankAccount' => false,
            'bankAccountForm' => $bankAccountForm->createView()
        ]);
    }

    /**
     * @Route("/app/wallet/bank-account/edit", name="app_wallet_bank-account_edit")
     */
    public function setBankAccountInformations(
        Request $request,
        BankAccountHandler $bankAccountHandler
    ): Response {
        $user = $this->getUser();
        $bankAccount = $user->getBankAccount();
        $bankAccountForm = $this->createForm(BankAccountType::class, $bankAccount);
        $bankAccountForm->handleRequest($request);

        if ($bankAccountForm->isSubmitted() && $bankAccountForm->isValid()) {
            $bankAccountHandler->process($bankAccountForm);
            $this->addFlash('success', 'Vos coordonnées bancaires ont été mises à jour !');

            return $this->redirectToRoute('app_wallet_bank-account');
        }
        return $this->render('wallet/bank-account.html.twig', [
            'user' => $user,
            'editBankAccount' => true,
            'bankAccountForm' => $bankAccountForm->createView()
        ]);
    }
}
