<?php

namespace App\Entity;

use App\Repository\CompetitionRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CompetitionRepository::class)
 */
class Competition
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *      message="Nom vide",
     * )
     * @Assert\Type(
     *     type="string",
     *     message="Format incorrect"
     * )
     * @Assert\Regex(
     *  pattern =  "/^[a-zA-Z0-9À-ÿ '-]{2,40}$/",
     *  message="Format Nom incorrect, 2 caractères minimum, 40 maximum",
     * )
     */
    private string $name;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(
     *      message="Date start vide",
     * )
     * @Assert\Type(
     *     type="datetime",
     *     message="Format incorrect"
     * )
     */
    private DateTimeInterface $startAt;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(
     *      message="Date start vide",
     * )
     * @Assert\Type(
     *     type="datetime",
     *     message="Format incorrect"
     * )
     * @Assert\GreaterThan(propertyPath="startAt",
     *     message="Incorrect End before Start"
     * )
     */
    private DateTimeInterface $endAt;


    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="competition")
     * @var Collection<int, Event>|null
     */
    private ?Collection $event;


    public function __construct()
    {
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

    public function getStartAt(): ?DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    /**
     * Entity builder with the requested parameters
     *
     * @param string|null $name
     * @param string|null $startAt
     * @param string|null $endAt
     * @return  self
     */
    public static function build(
        ?string $name,
        ?string $startAt,
        ?string $endAt
    ): Competition {
        $competition = new Competition();
        $name ? $competition->setName($name) : null;
        $startAt ? $competition->setStartAt(DateTime::createFromFormat('Y-m-d', $startAt)) : null;
        $endAt ? $competition->setEndAt(DateTime::createFromFormat('Y-m-d', $endAt)) : null;

        return $competition;
    }

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
            $event->setCompetition($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->event->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getCompetition() === $this) {
                $event->setCompetition(null);
            }
        }

        return $this;
    }
}
