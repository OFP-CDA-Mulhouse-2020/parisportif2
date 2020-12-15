<?php

namespace App\Entity;

use App\Repository\BetRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BetRepository::class)
 */
class Bet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(
     *  message="Date limite vide",
     *  groups={"limitTime"}
     * )
     * @Assert\GreaterThan(value="+1 hours",
     *  message="Date limite incorrecte",
     *  groups={"limitTime"}
     * )
     */
    private DateTimeInterface $betLimitTime;

    /**
     * @ORM\Column(type="array")
     * @Assert\NotBlank(
     *  message="Liste vide",
     * )
     */
    private array $listOfOdds = [];

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(
     *  message="Type de pari vide",
     * )
     * @Assert\PositiveOrZero(
     *  message="Type of Bet Id positive or zero",
     * )
     */
    private int $typeOfBetId;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank(
     *  message="Status du pari vide",
     * )
     */
    private bool $betOpened;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getListOfOdds(): ?array
    {
        return $this->listOfOdds;
    }

    public function setListOfOdds(array $listOfOdds): self
    {
        $this->listOfOdds = $listOfOdds;

        return $this;
    }

    public function getBetLimitTime(): ?DateTimeInterface
    {
        return $this->betLimitTime;
    }

    public function setBetLimitTime(DateTimeInterface $betLimitTime): self
    {
        $this->betLimitTime = $betLimitTime;

        return $this;
    }

    public function getTypeOfBetId(): ?int
    {
        return $this->typeOfBetId;
    }

    public function isOpen(): ?bool
    {
        return $this->betOpened;
    }

    public function setTypeOfBetId(int $typeOfBetId): self
    {
        $this->typeOfBetId = $typeOfBetId;

        return $this;
    }

    public function openBet(): void
    {
        $this->betOpened = true;
    }

    public function closeBet(): void
    {
        $this->betOpened = false;
    }

    /**
     * Entity builder with the requested parameters
     *
     * @param string|null $betLimitTime
     * @param array|null $listOfOdds
     * @param int|null $typeOfBetId
     * @return  self
     */
    public static function build(
        ?string $betLimitTime,
        ?array $listOfOdds,
        ?int $typeOfBetId
    ): Bet {
        $bet = new Bet();
        $betLimitTime ? $bet->setBetLimitTime(DateTime::createFromFormat('Y-m-d', $betLimitTime)) : null ;
        $listOfOdds ? $bet->setListOfOdds($listOfOdds) : null ;
        $typeOfBetId ? $bet->setTypeOfBetId($typeOfBetId) : null ;

        $bet->openBet();

        return $bet;
    }
}
