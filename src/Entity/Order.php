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
     * @Assert\Positive(
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
     * @Assert\Positive(
     *  message="Bet Id must be positive",
     * )
     */
    private int $recordedOdds;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(
     *  message="Amount incorrect",
     * )
     * @Assert\Type(
     *  type="integer",
     *  message="{{ value }} n'est pas du type {{ type }}",
     * )
     * @Assert\Positive(
     *  message="Bet Id must be positive",
     * )
     */
    private int $amount;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(
     *  message="Date de commande vide",
     * )
     * @Assert\LessThanOrEqual(
     *  value="+1 hours",
     *  message="Date de commande incorrecte : {{ value }}",
     * )
     */
    private DateTimeInterface $orderAt;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(
     *  message="Order Status incorrect",
     * groups={"orderStatus"}
     * )
     * @Assert\Choice(
     *  choices=Order::ORDER_STATUS,
     *  message="Status incorrect",
     *  groups={"orderStatus"}
     * )
     */
    private int $orderStatus;


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
     * @return int
     */
    public function getRecordedOdds(): int
    {
        return $this->recordedOdds;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
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
    public function getOrderStatus(): int
    {
        return $this->orderStatus;
    }

    public function placeAnOrder(int $betId, int $recordedOdds, int $amount)
    {
        $this->betId = $betId;
        $this->recordedOdds = $recordedOdds;
        $this->amount = $amount;
        $this->orderAt = new DateTime();
        $this->orderStatus = 0;
    }

    public function setOrderStatus(int $orderStatus): void
    {
        $this->orderStatus = $orderStatus;
    }

    public function calculateProfits(): ?int
    {
        $profits = null;

        switch ($this->getOrderStatus()) {
            case 0:
            case 3:
                $profits = null;
                break;
            case 2:
                $profits = $this->getRecordedOdds() * $this->getAmount();
                $profits /= 100;
                break;
            case 1:
            case 4:
                $profits = $this->getAmount();
                break;
        }

        return $profits;
    }
}
