<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TeamRepository::class)
 */
class Team
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
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     *@Assert\Regex(
     *  pattern =  "/^[a-zA-Z0-9À-ÿ '-]{2,40}$/",
     *  message="Format Nom incorrect, 2 caractères minimum, 40 maximum",
     * )
     */
    private string $sport;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Type(
     *  type="integer",
     *  message="{{ value }} n'est pas du type {{ type }}",
     * )
     * @Assert\PositiveOrZero(
     *  message="Team Status must be positive",
     * )
     */
    private int $nbPlayer;

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
     * @ORM\OneToMany(targetEntity=Player::class, mappedBy="team")
     */
    private $player;




    public function __construct()
    {
        $this->player = new ArrayCollection();
    }




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSport(): ?string
    {
        return $this->sport;
    }

    public function setSport(?string $sport): self
    {
        $this->sport = $sport;

        return $this;
    }

    public function getNbPlayer(): ?int
    {
        return $this->nbPlayer;
    }

    public function setNbPlayer(?int $nbPlayer): self
    {
        $this->nbPlayer = $nbPlayer;

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
     * @param string|null $name
     * @param string|null $sport
     * @param int|null $nbPlayer
     * @param int|null $ranking
     * @return  self
     * @throws \Exception
     */
    public static function build(
        ?string $name,
        ?string $sport,
        ?int $nbPlayer,
        ?int $ranking
    ): Team {
        $team = new Team();
        $name ? $team->setName($name) : null;
        $sport ? $team->setSport($sport) : null;
        $nbPlayer ? $team->setNbPlayer($nbPlayer) : null;
        $ranking ? $team->setranking($ranking) : null;


        return $team;
    }

    /**
     * @return Collection|Player[]
     */
    public function getPlayer(): Collection
    {
        return $this->player;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->player->contains($player)) {
            $this->player[] = $player;
            $player->setTeam($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->player->removeElement($player)) {
            // set the owning side to null (unless already changed)
            if ($player->getTeam() === $this) {
                $player->setTeam(null);
            }
        }

        return $this;
    }
}
