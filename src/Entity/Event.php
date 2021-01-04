<?php

namespace App\Entity;

use App\Repository\EventRepository;
use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 2,
     *      max = 40,
     *      minMessage = "Name must be at least {{ limit }} characters long",
     *      maxMessage = "Name cannot be longer than {{ limit }} characters"
     * )
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *      message="Lieu vide",
     * )
     * @Assert\Length(
     *      min = 2,
     *      max = 40,
     *      minMessage = "Location must be at least {{ limit }} characters long",
     *      maxMessage = "Location cannot be longer than {{ limit }} characters"
     * )
     */
    private string $location;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(
     *      message="Date vide",
     * )
     * @Assert\Type(
     *      value="datetime",
     *      message="Incorrect Date format",
     * )
     */
    private DateTimeInterface $eventDateTime;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *      message="Timezone vide",
     * )
     * @Assert\Timezone(
     *     message="Incorrect Timezone"
     * )
     */
    private string $eventTimeZone;

    /**
     * @ORM\ManyToOne(targetEntity=Competition::class, inversedBy="event")
     */
    private ?Competition $competition;

    /**
     * @ORM\ManyToMany(targetEntity=Team::class, mappedBy="event")
     * @var Collection<int, Team>|null
     */
    private ?collection $teams;

    /**
     * @ORM\ManyToOne(targetEntity=Sport::class, inversedBy="events")
     */
    private ?Sport $sport;




    public function __construct()
    {
        $this->teams = new ArrayCollection();
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

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getEventDateTime(): ?DateTimeInterface
    {
        return $this->eventDateTime;
    }

    public function setEventDateTime(DateTimeInterface $eventDateTime): self
    {
        $this->eventDateTime = $eventDateTime;

        return $this;
    }

    public function getEventTimeZone(): ?string
    {
        return $this->eventTimeZone;
    }

    public function setEventTimeZone(string $eventTimeZone): self
    {
        $this->eventTimeZone = $eventTimeZone;

        return $this;
    }

    /**
     * Entity builder with the requested parameters
     *
     * @param string|null $name
     * @param string|null $location
     * @param string|null $eventDateTime
     * @param string|null $eventTimeZone
     * @return  self
     * @throws \Exception
     */
    public static function build(
        ?string $name,
        ?string $location,
        ?string $eventDateTime,
        ?string $eventTimeZone
    ): Event {
        $event = new Event();
        $name ? $event->setName($name) : null;
        $location ? $event->setLocation($location) : null;
        $eventDateTime ? $event->setEventDateTime(new DateTime(
            $eventDateTime,
            new DateTimeZone($eventTimeZone)
        )) : null;
        $eventTimeZone ? $event->setEventTimeZone($eventTimeZone) : null;

        return $event;
    }




    public function getCompetition(): ?Competition
    {
        return $this->competition;
    }

    public function setCompetition(?Competition $competition): self
    {
        $this->competition = $competition;

        return $this;
    }



    /**
     * @return Collection<int, Team>|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams[] = $team;
            $team->addEvent($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->removeElement($team)) {
            $team->removeEvent($this);
        }

        return $this;
    }


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
     *  message= "Le nombre d'équipe est insuffisant !",
     *  groups={"isEnoughTeams"}
     *)
     * @return boolean
     */
    public function isEnoughTeams(): bool
    {
        $minContestantsAllowed = $this->getsport()->getNbOfTeams();

        if (count($this->getTeams()) == $minContestantsAllowed) {
            return true;
        }
        return false;
    }

    // /**
    //  * @assert\IsTrue(
    //  *  message= "Le nombre de joueurs est insuffisant !",
    //  *  groups={"isEnoughPlayers"}
    //  *)
    //  * @return boolean
    //  */
    // public function isEnoughPlayers(): bool
    // {
    //     $minPlayersAllowed = $this->getsport()->getNbOfPlayers();

    //     if (count($this->getPlayers()) == $minPlayersAllowed) {
    //         return true;
    //     }
    //     return false;
    // }
}
