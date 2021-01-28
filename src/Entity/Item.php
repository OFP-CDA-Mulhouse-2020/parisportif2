<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 */
class Item
{
    public const ITEM_STATUS = [
        0 => 'en attente', // order init => attente du paiement
        1 => 'pari payée', // order payment => joueur à payé son ticket
        2 => 'pari gagné', // order delivered => joueur à gagné son pari
        3 => 'pari perdu', // order delivered => joueur à perdu son pari
        4 => 'pari remboursé', // order cancelled => pari à été annulé et la mise aussi avec remboursement
        5 => 'pari soldé', // order closed => commande soldée
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(
     *  message="BetExpectedResult incorrect",
     * )
     * @Assert\Type(
     *  type="integer",
     *  message="{{ value }} n'est pas du type {{ type }}",
     * )
     * @Assert\PositiveOrZero(
     *  message="BetExpectedResult must be positive",
     * )
     */
    private int $expectedBetResult;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(
     *  message="recordedOdds incorrect",
     * )
     * @Assert\Type(
     *  type="integer",
     *  message="{{ value }} n'est pas du type {{ type }}",
     * )
     * @Assert\GreaterThan(
     *  value=100,
     *  message="recordedOdds must be positive",
     * )
     */
    private int $recordedOdds;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(
     *      message="Amount incorrect",
     * )
     * @Assert\Type(
     *      type="integer",
     *      message="{{ value }} n'est pas du type {{ type }}",
     * )
     * @Assert\Positive(
     *      message="Amount must be positive",
     * )
     */
    private int $amount;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(
     *      message="Order Status incorrect",
     * )
     * @Assert\Choice(
     *      choices={0,1,2,3,4,5},
     *      message="Choix status incorrect",
     * )
     */
    private int $itemStatusId;


    /**
     * @ORM\ManyToOne(targetEntity=Bet::class)
     * @Assert\NotNull(
     *      message="Bet incorrect",
     * )
     */
    private Bet $bet;

    /**
     * @ORM\ManyToOne(targetEntity=Cart::class, inversedBy="items")
     */
    private ?Cart $cart;

    /**
     * @ORM\ManyToOne(targetEntity=Payment::class, inversedBy="items")
     */
    private ?Payment $payment;

    public function __construct(Bet $bet)
    {
        $this->bet = $bet;
        $this->itemStatusId = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount / 100;
    }

    public function isModifiedAmount(float $amount): bool
    {
        if ($this->itemStatusId === 0 && $amount > 0) {
            $this->amount = (int) ($amount * 100);
            return true;
        }
        return false;
    }

    /**
     * @return int
     */
    public function getExpectedBetResult(): int
    {
        return $this->expectedBetResult;
    }

    /**
     * @return self
     */
    public function setExpectedBetResult(int $expectedBetResult): self
    {
        $this->expectedBetResult = $expectedBetResult;

        return $this;
    }

    /**
     * @return float
     */
    public function getRecordedOdds(): float
    {
        return $this->recordedOdds / 100;
    }

    /**
     * @return bool
     */
    public function isModifiedRecordedOdds(float $recordedOdds): bool
    {
        if ($this->itemStatusId === 0 && $recordedOdds > 0) {
            $this->recordedOdds = (int) ($recordedOdds * 100);
            return true;
        }
        return false;
    }

    /**
     * @return int
     */
    public function getItemStatusId(): int
    {
        return $this->itemStatusId;
    }

    public function payItem(): void
    {
        $this->itemStatusId = 1;
    }

    public function winItem(): void
    {
        $this->itemStatusId = 2;
    }

    public function looseItem(): void
    {
        $this->itemStatusId = 3;
    }

    public function refundItem(): void
    {
        $this->itemStatusId = 4;
    }

    public function closeItem(): void
    {
        $this->itemStatusId = 5;
    }

    public function getBet(): ?Bet
    {
        return $this->bet;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): self
    {
        $this->payment = $payment;

        return $this;
    }


    public function calculateProfits(): ?float
    {
        $profits = null;

        switch ($this->getItemStatusId()) {
            case 0:
            case 1:
            case 3:
            case 5:
                $profits = null;
                break;
            case 2:
                $profits = $this->getRecordedOdds() * $this->getAmount();
                break;
            case 4:
                $profits = $this->getAmount();
                break;
        }

        return $profits;
    }
}
