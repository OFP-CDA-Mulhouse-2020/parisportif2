<?php

namespace App\Entity;

use App\Repository\BetRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BetRepository::class)
 */
class Bet implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(
     *  message="Date limite vide",
     *  groups={"limitTime", "bet"}
     * )
     * @Assert\GreaterThan(value="today",
     *      message="Date limite incorrecte",
     *      groups={"limitTime", "bet"}
     * )
     */
    private DateTimeInterface $betLimitTime;

    /**
     * @ORM\Column(type="array")
     * @Assert\NotBlank(
     *      message="Liste vide",
     *      groups={"bet"}
     * )
     * @Assert\Valid
     */
    private array $listOfOdds = [];

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank(
     *      message="Status du pari vide",
     *      groups={"bet", "betStatus"}
     * )
     */
    private bool $betOpened;

    /**
     * @ORM\ManyToOne(targetEntity=TypeOfBet::class)
     * @Assert\NotNull(
     *      message="Type of Bet is empty",
     *     groups={"bet"}
     * )
     * @Assert\Valid
     */
    private TypeOfBet $typeOfBet;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class)
     */
    private ?Event $event;

    /**
     * @ORM\Column(type="array")
     */
    private array $betResult = [];

    /**
     * @Assert\Valid
     */
    private array $oddsList;


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

    public function isBetOpened(): ?bool
    {
        return $this->betOpened;
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
        $typeOfBetId !== null ? $bet->setTypeOfBet(new TypeOfBet()) : null ;

        $bet->openBet();

        return $bet;
    }

    public function getTypeOfBet(): ?TypeOfBet
    {
        return $this->typeOfBet;
    }

    public function setTypeOfBet(TypeOfBet $typeOfBet): self
    {
        $this->typeOfBet = $typeOfBet;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getBetResult(): ?array
    {
        return $this->betResult;
    }

    public function setBetResult(array $betResult): self
    {
        $this->betResult = $betResult;

        return $this;
    }

    /**
     * @param bool $betOpened
     */
    public function setBetOpened(bool $betOpened): void
    {
        $this->betOpened = $betOpened;
    }

    /**
     * @return array
     */
    public function getOddsList(): array
    {
        return $this->oddsList;
    }

    /**
     * @param array $oddsList
     */
    public function setOddsList(array $oddsList): void
    {
        $this->oddsList = $oddsList;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'listOfOdds' => $this->getListOfOdds(),
            'typeOfBet' => $this->getTypeOfBet(),
            'event' => $this->getEvent(),
        ];
    }
}
