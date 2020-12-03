<?php

namespace App\Entity;

use App\Repository\User1Repository;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use DateTimeInterface;
use DateTime;
use DateTimeZone;

/**
 * @ORM\Entity(repositoryClass=User1Repository::class)
 */
class User1
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $password;

    // /**
    //  * @ORM\Column(type="DateTimeInterface", length=255)
    //  */
    private DatetimeInterface $birthDate;


    private const PATTERN_NAME = "/^[a-zA-ZÀ-ÿ '-]{1,30}$/";
    private const PATTERN_PASS = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,16}$/';



    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        if (!is_int($id) || $id < 0) {
            throw new InvalidArgumentException('This is not an integer Number');
        }

        $this->id = $id;

        return $this;
    }



    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        if (!preg_match(self::PATTERN_NAME, $name)) {
            throw new InvalidArgumentException('The Name is Invalid');
        }

        $this->name = $name;

        return $this;
    }



    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        if (!preg_match(self::PATTERN_NAME, $firstName)) {
            throw new InvalidArgumentException('The firstname is Invalid');
        }

        $this->firstName = $firstName;

        return $this;
    }



    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Email format is not respected');
        }
        $this->email = $email;

        return $this;
    }



    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        if (strlen($password) < 8) {
            throw new InvalidArgumentException('Password length is too short !');
        }
        if (!preg_match(self::PATTERN_PASS, $password)) {
            throw new InvalidArgumentException('Password must contain at least one number and an uppercase letter !');
        }

        // $this->password = $password;
        $this->password = password_hash($password, PASSWORD_DEFAULT);

        return $this;
    }




    // final public function createdAt(): DateTimeInterface
    // {
    //     return $this->createdAt;
    // }

    // final public function setBirthDate(DateTimeInterface $birthDate): self
    // {
    //     $this->birthDate = $birthDate;

    //     return $this;
    // }

    // final public function isUserOldEnough(): bool
    // {
    //     $now = new DateTime();
    //     $now
    //         ->setTimezone(new DateTimeZone("Europe/Paris"))
    //         ->setTime(0, 0);

    //     return $now->diff($this->getBirthDate()) >= "18";
    // }

    // private function getBirthDate(): DateTimeInterface
    // {
    //     return $this->birthDate;
    // }

    // final public function getTimeZone(): DateTimeZone
    // {
    //     return $this->timeZone;
    // }




    public function setBirthDate(DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }


    public function getBirthDate(): DateTimeInterface
    {
        return $this->birthDate;
    }



    // public function getBirthDate()
    // {
    //     return $this->birthDate;
    // }

    // public function setBirthDate(DateTimeInterface $birthDate): self
    // {

    //     $this->birthDate = $birthDate;

    //     return $this;
    // }
}
