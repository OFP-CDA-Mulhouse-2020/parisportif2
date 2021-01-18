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
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     *@Assert\Regex(
     *  pattern =  "/^[a-zA-Z0-9À-ÿ '-]{2,40}$/",
     *  message="Format Nom incorrect, 2 caractères minimum, 40 maximum",
     * )
     */
    private string $name;

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
     * @var Collection<int, Player>|null
     *
     * @assert\NotNull
     */
    private ?Collection $player;

    /**
     * @ORM\ManyToOne(targetEntity=Sport::class)
     */
    private ?Sport $sport;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, inversedBy="teams")
     * @var Collection<int, Event>|null
     */
    private ?Collection $event;

    public function __construct()
    {
        $this->player = new ArrayCollection();
        $this->event = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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
     * @param string|null $name
     * @param int|null $ranking
     * @return  self
     * @throws \Exception
     */
    public static function build(
        ?string $name,
        ?int $ranking
    ): Team {
        $team = new Team();
        $name ? $team->setName($name) : null;
        $team->setSport(new Sport());
        $ranking ? $team->setranking($ranking) : null;

        return $team;
    }

    /***** Relation to Event */

    /**
     * @return Collection<int, Event>|Event[]
     */
    public function getEvent(): Collection
    {
        return $this->event;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->event->contains($event)) {
            $this->event[] = $event;
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        $this->event->removeElement($event);

        return $this;
    }

    /***** Relation to Player */

    /**
     * @return Collection<int, Player>|Player[]
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

    /***** Relation to Sport */

    public function getSport(): ?Sport
    {
        return $this->sport;
    }

    public function setSport(?Sport $sport): self
    {
        $this->sport = $sport;

        return $this;
    }

    /**
     * @assert\IsTrue(
     *  message= "Le nombre de joueurs est insuffisant !",
     *  groups={"isEnoughPlayers"}
     *)
     * @return boolean
     */
    public function isEnoughPlayers()
    {
        $minPlayersAllowed = $this->getSport()->getNbOfPlayers();

        if (count($this->getPlayer()) < $minPlayersAllowed) {
            return false;
        }
        return true;
    }
}
