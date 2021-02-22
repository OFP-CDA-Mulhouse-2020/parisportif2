<?php

namespace App\Service;

use App\Entity\Bet;
use App\Entity\Item;
use App\Entity\Payment;
use App\Entity\TypeOfPayment;
use App\Entity\User;
use App\Entity\Wallet;
use App\Factory\PaymentFactory;
use App\Repository\BetRepository;
use App\Repository\ItemRepository;
use App\Repository\TypeOfPaymentRepository;
use App\Repository\WebsiteWalletRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GenerateBetPaymentService
{
    private EntityManagerInterface $entityManager;
    private BetRepository $betRepository;
    private ItemRepository $itemRepository;
    private TypeOfPaymentRepository $typeOfPaymentRepository;
    private WebsiteWalletRepository $websiteWalletRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        BetRepository $betRepository,
        ItemRepository $itemRepository,
        TypeOfPaymentRepository $typeOfPaymentRepository,
        WebsiteWalletRepository $websiteWalletRepository
    ) {
        $this->entityManager = $entityManager;
        $this->betRepository = $betRepository;
        $this->itemRepository = $itemRepository;
        $this->typeOfPaymentRepository = $typeOfPaymentRepository;
        $this->websiteWalletRepository = $websiteWalletRepository;
    }


    public function validateBetToPayment(Bet $bet): void
    {
        /** @var array $result */
        $result = $bet->getBetResult();

        $listOfItems = $this->itemRepository->findBy(['bet' => $bet->getId(), 'itemStatusId' => 1]);

        foreach ($listOfItems as $item) {
            $expectedResult = $item->getExpectedBetResult();

            if (in_array($expectedResult, $result)) {
                $item->winItem();
            } else {
                $item->looseItem();
            }

            if ($item->calculateProfits() !== null) {
                $this->processPayment($item);
            }

            $this->entityManager->persist($item);
            $this->entityManager->flush();
        }
    }


    private function processPayment(Item $item): void
    {
        $wallet = $item->getPayment()->getWallet();
        $sum = $item->calculateProfits();

        $typeOfPayment = $this->typeOfPaymentRepository->findOneBy(
            [
                'typeOfPayment' => 'Internal Transfer Bet Earning'
            ]
        );
        assert($typeOfPayment instanceof TypeOfPayment);
        $websiteWallet = $this->websiteWalletRepository->findAll()[0];

        $payment = PaymentFactory::makePaymentForBetEarning($sum, $wallet, $websiteWallet, $typeOfPayment);


        $walletStatus = $wallet->addMoney($sum);

        if (!$walletStatus) {
            throw new \LogicException("Montant incorrect");
        }

        $payment->acceptPayment();
        $websiteWallet->removeFromBalance($sum);

        $this->entityManager->persist($wallet);
        $this->entityManager->persist($payment);
    }
}
