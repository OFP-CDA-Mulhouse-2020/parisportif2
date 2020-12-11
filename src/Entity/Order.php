<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    public const ORDER_STATUS = [
        0, // order payment => joueur à payé son ticket
        1, // order delivered => joueur à gagné ou perdu son pari
        2, // order cancelled => pari à été annulé et la mise aussi avec remboursement
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
    private int $recordedOdd;

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
    private int $amount;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(
     *  message="Date de commande vide",
     *  groups={"createAt"}
     * )
     * @Assert\LessThanOrEqual(
     *  value="+1 hours",
     *  message="Date de commande incorrecte : {{ value }}",
     *  groups={"createAt"}
     * )
     */
    private DateTimeInterface $orderAt;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(
     *  message="Bet Id incorrect",
     * )
     * @Assert\Type(
     *  type="integer",
     *  message="{{ value }} n'est pas du type {{ type }}",
     * )
     * @Assert\Choice(
     *  choices=Order::ORDER_STATUS, message="Status incorrect")
     * )
     */
    private $orderStatus;



    public function getId(): ?int
    {
        return $this->id;
    }
/*
    public static function build(int $betId, int $recordedOdd, int $amount): Order
    {

        $this->betId = $betId;
        $this->recordedOdd = $recordedOdd;
        $this->amount = $amount;


    }

*/
}
