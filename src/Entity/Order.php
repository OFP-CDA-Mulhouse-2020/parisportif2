<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    public const ORDER_STATUS = [
        0, // order init => attente du paiement
        1, // order payment => joueur à payé son ticket
        2, // order delivered => joueur à gagné son pari
        3, // order delivered => joueur à perdu son pari
        4, // order cancelled => pari à été annulé et la mise aussi avec remboursement
        5, // order closed => commande soldée
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
     *  message="Bet Id incorrect",
     * )
     * @Assert\Type(
     *  type="integer",
     *  message="{{ value }} n'est pas du type {{ type }}",
     * )
     * @Assert\PositiveOrZero(
     *  message="Bet Id must be positive",
     * )
     */
    private int $betId;

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
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(
     *      message="Date de commande vide",
     * )
     * @Assert\LessThanOrEqual(
     *      value="+1 hours",
     *      message="Date de commande incorrecte : {{ value }}",
     * )
     */
    private DateTimeInterface $orderAt;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(
     *      message="Order Status incorrect",
     *      groups={"orderStatus"}
     * )
     * @Assert\Choice(
     *      choices=Order::ORDER_STATUS,
     *      message="Status incorrect",
     *      groups={"orderStatus"}
     * )
     */
    private int $orderStatusId;

    public function __construct(int $betId, float $recordedOdds)
    {
        $this->betId = $betId;
        $this->recordedOdds = (int) ($recordedOdds * 100);
        $this->orderAt = new DateTime();
        $this->orderStatusId = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getBetId(): int
    {
        return $this->betId;
    }

    /**
     * @return float
     */
    public function getRecordedOdds(): float
    {
        return $this->recordedOdds / 100;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount / 100;
    }

    /**
     * @return DateTimeInterface
     */
    public function getOrderAt(): DateTimeInterface
    {
        return $this->orderAt;
    }

    /**
     * @return int
     */
    public function getOrderStatusId(): int
    {
        return $this->orderStatusId;
    }

    /**
     * @param float $amount
     */
    public function setAmount(float $amount): self
    {
        $this->amount = (int) ($amount * 100);

        return $this;
    }

    public function payOrder(): void
    {
        $this->orderStatusId = 1;
    }

    public function winOrder(): void
    {
        $this->orderStatusId = 2;
    }

    public function looseOrder(): void
    {
        $this->orderStatusId = 3;
    }

    public function refundOrder(): void
    {
        $this->orderStatusId = 4;
    }

    public function closeOrder(): void
    {
        $this->orderStatusId = 5;
    }

    public function calculateProfits(): ?float
    {
        $profits = null;

        switch ($this->getOrderStatusId()) {
            case 0:
                $profits = - $this->getAmount();
                break;
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
