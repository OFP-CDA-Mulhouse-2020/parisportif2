<?php

namespace App\Entity;

use App\Repository\WebsiteWalletRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=WebsiteWalletRepository::class)
 */
class WebsiteWallet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(
     *      message="Balance is incorrect",
     * )
     * @Assert\Type(
     *      type="integer",
     *      message="{{ value }} n'est pas du type {{ type }}",
     * )
     * @Assert\PositiveOrZero(
     *      message="Balance must be positive or equals to zero",
     * )
     */
    private int $balance;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function initializeWallet()
    {
        $this->balance = 100000 * 100;
    }



    /******************************** balance ****************************** */

    public function getBalance(): float
    {
        return $this->balance / 100;
    }

    public function addToBalance($money): float
    {
        if ($money > 0) {
            $this->balance += $money * 100;
        }
        return $this->balance;
    }

    public function removeFromBalance($money): float
    {
        if ($money > 0 && $money <= $this->getBalance()) {
            $this->balance -= $money * 100;
        }

        return $this->balance;
    }
}
