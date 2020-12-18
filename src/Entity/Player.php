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
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     *@Assert\Regex(
     *  pattern =  "/^[a-zA-Z0-9À-ÿ '-]{2,40}$/",
     *  message="Format Nom incorrect, 2 caractères minimum, 40 maximum",
     * )
     */
    private string $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     *@Assert\Regex(
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


    /**
     * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="player")
     */
    private $team;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getPlayerStatus(): ?int
    {
        return $this->playerStatus;
    }

    public function setPlayerStatus(?int $playerStatus): self
    {
        $this->playerStatus = $playerStatus;

        return $this;
    }

    public function getRanking(): ?int
    {
        return $this->ranking;
    }

    public function setRanking(?int $ranking): self
    {
        $this->ranking = $ranking;

        return $this;
    }


    /**
     * Entity builder with the requested parameters
     *
     * @param string|null $lastName
     * @param string|null $firstName
     * @param int|null $playerStatus
     * @param int|null $ranking
     * @return  self
     * @throws \Exception
     */
    public static function build(
        ?string $lastName,
        ?string $firstName,
        ?int $playerStatus,
        ?int $ranking
    ): Player {
        $player = new Player();
        $lastName ? $player->setLastName($lastName) : null;
        $firstName ? $player->setFirstName($firstName) : null;
        $playerStatus ? $player->setPlayerStatus($playerStatus) : null;
        $ranking ? $player->setranking($ranking) : null;


        return $player;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

        return $this;
    }
}
