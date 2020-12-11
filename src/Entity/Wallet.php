<?php

namespace App\Entity;

use App\Repository\WalletRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=WalletRepository::class)
 */
class Wallet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(
     *  message="Balance incorrect",
     * )
     * @Assert\Type(
     *  type="integer",
     *  message="{{ value }} n'est pas du type {{ type }}",
     * )
     * @Assert\PositiveOrZero(
     *  message="Balance not less than 0",
     * )
     */
    private int $balance;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(
     *  message="Limite incorrecte",
     *  groups={"limitAmountPerWeek"}
     * )
     * @Assert\PositiveOrZero(
     *  message="Limite incorrecte",
     *  groups={"limitAmountPerWeek"}
     * )
     * @Assert\LessThanOrEqual(
     *  value = 10000,
     *  message="Limite maximum 100,00 euros",
     *  groups={"limitAmountPerWeek"}
     * )
     */
    private int $limitAmountPerWeek = 10000;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotNull(
     *  message="Money incorrect",
     * )
     */
    private bool $realMoney;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getBalance(): int
    {
        return $this->balance;
    }

    /**
     * @return int
     */
    public function getLimitAmountPerWeek(): int
    {
        return $this->limitAmountPerWeek;
    }

    /**
     * @param int $limitAmountPerWeek
     */
    public function setLimitAmountPerWeek(int $limitAmountPerWeek): void
    {
        $this->limitAmountPerWeek = $limitAmountPerWeek;
    }

    /**
     * @return bool
     */
    public function isRealMoney(): bool
    {
        return $this->realMoney;
    }

    /**
     * @param bool $realMoney
     */
    public function setRealMoney(bool $realMoney): void
    {
        $this->realMoney = $realMoney;
    }

    public function initializeWallet(bool $realMoney): void
    {
        if ($realMoney) {
            $this->balance = 0;
        } else {
            $this->balance = 100;
        }
            $this->realMoney = $realMoney;
    }

    public function addMoney(int $amount): bool
    {
        if ($amount <= 0) {
            return false;
        }
        $this->balance += $amount;

        return true;
    }

    public function withdrawMoney(int $amount): bool
    {
        if ($amount <= 0 or $amount > $this->getBalance()) {
            return false;
        }
        $this->balance -= $amount;

        return true;
    }

    public function betPayment(int $amount, int $amountBetPaymentLastWeek): bool
    {
        if ($amount <= 0 or $amount > $this->getBalance()) {
            return false;
        }
        if ($amount > $this->getLimitAmountPerWeek() / 100 - $amountBetPaymentLastWeek) {
            return false;
        }
        $this->balance -= $amount;

        return true;
    }
}
