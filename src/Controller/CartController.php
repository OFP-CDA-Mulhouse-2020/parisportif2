<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Item;
use App\Entity\Payment;
use App\Entity\TypeOfPayment;
use App\Repository\BetRepository;
use App\Repository\ItemRepository;
use App\Repository\TypeOfPaymentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("app/cart/add/{id}/{expectedResult}", name="app_cart_add_item")
     */
    public function addItemToCart(int $id, int $expectedResult, BetRepository $betRepository): Response
    {
        $user = $this->getUser();
        if ($user->getCart() === null) {
            $cart = new Cart();
            $user->setCart($cart);
        } else {
            $cart = $user->getCart();
        }

        $bet = $betRepository->find($id);
        $odds = $bet->getListOfOdds()[$expectedResult];

        $item = new Item($bet);
        $item->setExpectedBetResult($expectedResult);
        $item->isModifiedRecordedOdds($odds);
        $item->isModifiedAmount(5);

        $cart->addItem($item);
        $cart->setSum();

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($item);
        $entityManager->persist($cart);
        $entityManager->flush();

        return $this->redirectToRoute('app');
    }

    /**
     * @Route("app/cart/remove/{id}", name="app_cart_remove_item")
     */
    public function removeItemFromCart(int $id, ItemRepository $itemRepository): Response
    {
        $user = $this->getUser();
        $cart = $user->getCart();

        $item = $itemRepository->find($id);
        $cart->removeItem($item);
        $cart->setSum();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($item);

        if (count($cart->getItems()) === 0) {
            $user->setCart(null);
            $entityManager->remove($cart);
            $entityManager->persist($user);
        } else {
            $entityManager->persist($cart);
        }
        $entityManager->flush();

        return $this->redirectToRoute('app');
    }


    /**
     * @Route("app/cart/validate", name="app_cart_validate")
     */
    public function validateCart(TypeOfPaymentRepository $typeOfPaymentRepository): Response
    {
        $user = $this->getUser();
        $cart = $user->getCart();
        $sum = $cart->getSum();
        $payment = new Payment($sum);
        $payment->setWallet($user->getWallet());
        $payment->setItems($cart->getItems());

        $typeOfPayment = $typeOfPaymentRepository->findOneBy(
            [
                'typeOfPayment' => 'Internal Transfer Bet Payment'
            ]
        );

        $payment->setTypeOfPayment($typeOfPayment);

        dd($payment);

        return $this->redirectToRoute('app');
    }
}
