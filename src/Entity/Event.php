<?php

namespace App\Entity;

use App\Repository\EventRepository;
use DateTime;
use DateTimeInterface;
use DateTimeZone;
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
        $name ? $event->setName($name) : null ;
        $location ? $event->setLocation($location) : null ;
        $eventDateTime ? $event->setEventDateTime(new DateTime(
            $eventDateTime,
            new DateTimeZone($eventTimeZone)
        )) : null ;
        $eventTimeZone ? $event->setEventTimeZone($eventTimeZone) : null ;

        return $event;
    }
}
