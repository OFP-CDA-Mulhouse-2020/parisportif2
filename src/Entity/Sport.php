<?php

namespace App\Entity;

use App\Repository\SportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SportRepository::class)
 */
class Sport implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Regex(
     *  pattern =  "/^[a-zA-Z0-9À-ÿ '-]{2,40}$/",
     *  message="Format Nom incorrect, 2 caractères minimum, 40 maximum",
     * )
     */
    private string $name = '';


    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Assert\Type(
     *  type="integer",
     *  message="{{ value }} n'est pas du type {{ type }}",
     * )
     * @Assert\PositiveOrZero(
     *  message="The number of teams must be positive",
     * )
     */
    private int $nbOfTeams;


    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Assert\Type(
     *  type="integer",
     *  message="{{ value }} n'est pas du type {{ type }}",
     * )
     * @Assert\PositiveOrZero(
     *  message=" The number of players must be positive",
     * )
     */
    private int $nbOfPlayers;



    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Assert\Type(
     *  type="integer",
     *  message="{{ value }} n'est pas du type {{ type }}",
     * )
     * @Assert\PositiveOrZero(
     *  message=" The number of substitute players must be positive",
     * )
     */
    private int $nbOfSubstitutePlayers;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="sport")
     * @var Collection<int, Event>|null
     */
    private ?Collection $events;

    /**
     * @ORM\OneToMany(targetEntity=Competition::class, mappedBy="sport")
     * @var Collection<int, Competition>|null
     */
    private ?Collection $competitions;




    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->competitions = new ArrayCollection();
    }


    public function __toString(): string
    {
        return $this->getName();
    }


    // Ajouter évenement

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getNbOfTeams(): ?int
    {
        return $this->nbOfTeams;
    }

    public function setNbOfTeams(int $nbOfTeams): self
    {
        $this->nbOfTeams = $nbOfTeams;

        return $this;
    }

    public function getNbOfPlayers(): ?int
    {
        return $this->nbOfPlayers;
    }

    public function setNbOfPlayers(int $nbOfPlayers): self
    {
        $this->nbOfPlayers = $nbOfPlayers;

        return $this;
    }

    public function getNbOfSubstitutePlayers(): ?int
    {
        return $this->nbOfSubstitutePlayers;
    }

    public function setNbOfSubstitutePlayers(int $nbOfSubstitutePlayers): self
    {
        $this->nbOfSubstitutePlayers = $nbOfSubstitutePlayers;

        return $this;
    }


    /**
     * Entity builder with the requested parameters
     *
     * @param string|null $name
     * @param int|null $nbOfTeams
     * @param int|null $nbOfPlayers
     * @param int|null $nbOfSubstitutePlayers
     * @return  self
     * @throws \Exception
     */
    public static function build(
        ?string $name,
        ?int $nbOfTeams,
        ?int $nbOfPlayers,
        ?int $nbOfSubstitutePlayers
    ): Sport {
        $sport = new Sport();
        $name ? $sport->setName($name) : null;
        $nbOfTeams ? $sport->setNbOfTeams($nbOfTeams) : null;
        $nbOfPlayers ? $sport->setNbOfPlayers($nbOfPlayers) : null;
        $nbOfSubstitutePlayers ? $sport->setNbOfSubstitutePlayers($nbOfSubstitutePlayers) : null;


        return $sport;
    }

    /**
     * @return Collection<int, Event>|null
     */
    public function getEvents(): ?Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setSport($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getSport() === $this) {
                $event->setSport(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Competition>|Competition[]
     */
    public function getCompetitions(): Collection
    {
        return $this->competitions;
    }

    public function addCompetition(Competition $competition): self
    {
        if (!$this->competitions->contains($competition)) {
            $this->competitions[] = $competition;
            $competition->setSport($this);
        }

        return $this;
    }

    public function removeCompetition(Competition $competition): self
    {
        if ($this->competitions->removeElement($competition)) {
            // set the owning side to null (unless already changed)
            if ($competition->getSport() === $this) {
                $competition->setSport(null);
            }
        }

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
        ];
    }
}
