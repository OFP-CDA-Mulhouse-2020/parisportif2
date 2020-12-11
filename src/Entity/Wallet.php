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
     * @Assert\Type(
     *  type="integer",
     *  message="{{ value }} n'est pas du type {{ type }}",
     *  groups={"balance"}
     * )
     * @Assert\PositiveOrZero(
     *  message="Balance not less than 0",
     *  groups={"balance"}
     * )
     */
    private int $balance;

    /**
     * @ORM\Column(type="integer")
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
     * @param int $balance
     */
    public function setBalance(int $balance): void
    {
        $this->balance = $balance;
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

    public static function build(int $balance, int $limitAmountPerWeek, bool $realMoney): Wallet
    {
        $wallet = new Wallet();
        $wallet->setBalance($balance);

        return $wallet;
    }

    /**
     * @Assert\IsFalse(
     *  message="Montant incorrect, transaction rejetée",
     *  groups={"addMoney"}
     * )
     */
    public function hasAddedMoney(int $amount): bool
    {
        if ($amount <= 0) {
            return false;
        }
        $this->balance += $amount;

        return true;
    }
}
