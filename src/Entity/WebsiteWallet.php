<?php

namespace App\Entity;

use App\Repository\WebsiteWalletRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private ?int $id = null;

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

    /**
     * @ORM\OneToMany(targetEntity=Payment::class, mappedBy="websiteWallet")
     * @var Collection<int, Payment>|null
     */
    private ?Collection $payments;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function initializeWallet(): void
    {
        $this->balance = 100000 * 100;
    }

    /******************************** balance ****************************** */

    public function getBalance(): float
    {
        return $this->balance / 100;
    }

    public function addToBalance(float $money): float
    {
        if ($money > 0) {
            $this->balance += (int) $money * 100;
        }
        return $this->balance;
    }

    public function removeFromBalance(float $money): float
    {
        if ($money > 0 && $money <= $this->getBalance()) {
            $this->balance -= (int) $money * 100;
        }

        return $this->balance;
    }

    /**
     * @return Collection<int, Payment>|Payment[]
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): self
    {
        if (!$this->payments->contains($payment)) {
            $this->payments[] = $payment;
            $payment->setWebsiteWallet($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): self
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getWebsiteWallet() === $this) {
                $payment->setWebsiteWallet(null);
            }
        }

        return $this;
    }
}
