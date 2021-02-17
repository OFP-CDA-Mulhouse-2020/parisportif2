<?php

namespace App\Dto;

use App\Entity\Bet;
use App\Entity\Payment;
use App\Entity\TypeOfPayment;
use App\Entity\User;
use App\Entity\Wallet;
use App\Repository\BetRepository;
use App\Repository\ItemRepository;
use App\Repository\TypeOfPaymentRepository;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GenerateBetPaymentDto
{
    private BetRepository $betRepository;
    private ItemRepository $itemRepository;
    private TypeOfPaymentRepository $typeOfPaymentRepository;

    public function __construct(
        BetRepository $betRepository,
        ItemRepository $itemRepository,
        TypeOfPaymentRepository $typeOfPaymentRepository
    ) {
        $this->betRepository = $betRepository;
        $this->itemRepository = $itemRepository;
        $this->typeOfPaymentRepository = $typeOfPaymentRepository;
    }


//    public function validateBetToPayment(
//AdminContext $context
//    ): Response {
//
//        $id = $context->getEntity()->getInstance()->getId();
//
//      //  dd($id);
//        $bet = $betRepository->find($id);
//        assert($bet instanceof Bet);
//
//        if ($bet->isBetOpened()) {
//            return new RedirectResponse($context->getReferrer());
//        }
//
//        $user = $this->getUser();
//        assert($user instanceof User);
//        $wallet = $user->getWallet();
//        assert($wallet instanceof Wallet);
//
//        /** @var array $result */
//        $result = $bet->getBetResult();
//
//        $listOfItems = $itemRepository->findBy(['bet' => $bet->getId(), 'itemStatusId' => 1]);
//        $entityManager = $this->getDoctrine()->getManager();
//
//        foreach ($listOfItems as $item) {
//            $expectedResult = $item->getExpectedBetResult();
//
//            if (in_array($expectedResult, $result)) {
//                $item->winItem();
//            } else {
//                $item->looseItem();
//            }
//
//            $sum = $item->calculateProfits();
//
//            if ($sum !== null) {
//                $typeOfPayment = $typeOfPaymentRepository->findOneBy(
//                    [
//                        'typeOfPayment' => 'Internal Transfer Bet Earning'
//                    ]
//                );
//                assert($typeOfPayment instanceof TypeOfPayment);
//
//                $payment = new Payment($sum);
//                $payment->setWallet($wallet);
//                $payment->setPaymentName('Gain sur ticket de pari n°');
//                $payment->setTypeOfPayment($typeOfPayment);
//
//                $walletStatus = $wallet->addMoney($sum);
//
//                if (!$walletStatus) {
//                    throw new \LogicException("Montant incorrect");
//                }
//                $payment->acceptPayment();
//
//                $entityManager->persist($wallet);
//                $entityManager->persist($payment);
//            }
//
//            $entityManager->persist($item);
//            $entityManager->flush();
//        }
//
//        return new RedirectResponse($request->server->get('HTTP_REFERER'));
//    }
}
