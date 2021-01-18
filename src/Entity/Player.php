<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PlayerRepository::class)
 */
class Player
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *  pattern =  "/^[a-zA-Z0-9À-ÿ '-]{2,40}$/",
     *  message="Format Nom incorrect, 2 caractères minimum, 40 maximum",
     * )
     */
    private string $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *  pattern =  "/^[a-zA-Z0-9À-ÿ '-]{2,40}$/",
     *  message="Format Nom incorrect, 2 caractères minimum, 40 maximum",
     * )
     */
    private string $firstName;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Type(
     *  type="integer",
     *  message="{{ value }} n'est pas du type {{ type }}",
     * )
     * @Assert\PositiveOrZero(
     *  message="Player Status must be positive",
     * )
     * @Assert\Choice(
     *  choices={0,1,2,3,4},
     *  message="Le status du joueur est incorrect",
     * )
     */
    private int $playerStatus;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Type(
     *  type="integer",
     *  message="{{ value }} n'est pas du type {{ type }}",
     * )
     * @Assert\PositiveOrZero(
     *  message="ranking must be positive",
     * )
     */
    private int $ranking;


    // /**
    //  * @ORM\OneToMany(targetEntity=Event::class, mappedBy="player")
    //  */
    // private $event;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="player")
     */
    private ?Team $team;

    /**
     * @ORM\ManyToOne(targetEntity=Sport::class)
     */
    private ?Sport $sport;


    public function __construct()
    {
        $this->playerStatus = 0;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getPlayerStatus(): ?int
    {
        return $this->playerStatus;
    }

    public function setPlayerStatus(int $playerStatus): self
    {
        $this->playerStatus = $playerStatus;

        return $this;
    }

    public function activeStatus(): void
    {
        $this->playerStatus = 1;
    }

    public function replacementStatus(): void
    {
        $this->playerStatus = 2;
    }

    public function injuredStatus(): void
    {
        $this->playerStatus = 3;
    }

    public function inactiveStatus(): void
    {
        $this->playerStatus = 4;
    }

    public function getRanking(): ?int
    {
        return $this->ranking;
    }

    public function setRanking(int $ranking): self
    {
        $this->ranking = $ranking;

        return $this;
    }


    /**
     * Entity builder with the requested parameters
     *
     * @param string|null $lastName
     * @param string|null $firstName
     * @param int|null $ranking
     * @return  self
     * @throws \Exception
     */
    public static function build(
        ?string $lastName,
        ?string $firstName,
        ?int $ranking
    ): Player {
        $player = new Player();
        $lastName ? $player->setLastName($lastName) : null;
        $firstName ? $player->setFirstName($firstName) : null;
        $ranking ? $player->setRanking($ranking) : null;


        return $player;
    }


    /***** Team */

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

        return $this;
    }


    /***** Sport */

    public function getSport(): ?Sport
    {
        return $this->sport;
    }

    public function setSport(?Sport $sport): self
    {
        $this->sport = $sport;

        return $this;
    }
}
