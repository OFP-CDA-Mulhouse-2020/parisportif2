<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"},
 * groups={"register"},
 * message="Il y a déjà un compte avec cet email")
 */
class User implements UserInterface
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Assert\Type(type="integer",
     * message="Id incorrect",
     * groups={"login"}
     * )
     * @Assert\GreaterThan(
     * value = 0,
     * message="Id incorrect",
     * groups={"login"}
     * )
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Nom vide",
     * groups={"username", "register"}
     * )
     * @Assert\Regex(
     * pattern =  "/^[a-zA-ZÀ-ÿ '-]{1,30}$/",
     * message="Nom : {{ value }} incorrect",
     * groups={"username", "register"}
     * )
     */
    private string $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Prénom vide",
     * groups={"username", "register"}
     * )
     * @Assert\Regex(
     * pattern =  "/^[a-zA-ZÀ-ÿ '-]{1,30}$/",
     * message="Prénom : {{ value }} incorrect",
     * groups={"username", "register"}
     * )
     */
    private string $firstName;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Email vide",
     * groups={"email","login", "register"}
     * )
     * @Assert\Email(message="Format email incorrect",
     * groups={"email","login", "register"}
     * )
     */
    private string $email;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Password vide",
     * groups={"password","login", "register"}
     * )
     * @Assert\Regex(
     * pattern =  "/^(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,16}$/",
     * message="Format password incorrect, 1 Majuscule, 1 Chiffre, 8 caractères minimum",
     * groups={"password","login", "register"}
     * )
     */
    private string $password;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="Date de naissance vide",
     * groups={"birthDate", "register"}
     * )
     * @Assert\LessThanOrEqual(value="-18 years",
     * message="Vous n'avez pas 18 ans minimum",
     * groups={"birthDate", "register"}
     * )
     */
    private ?DateTimeInterface $birthDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="Date de création vide",
     * groups={"createDate", "subscribe"}
     * )
     * @Assert\LessThanOrEqual(value="today",
     * message="Date de création incorrecte",
     * groups={"createDate", "subscribe"}
     * )
     */
    private DateTimeInterface $createDate;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\Type(
     * type="bool",
     * message="{{ value }} n'est pas du type {{ type }}"
     * )
     */
    private bool $userValidation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $userValidationDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $userSuspended;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $userSuspendedDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $userDeleted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $userDeletedDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    public function getUserValidation(): ?bool
    {
        return $this->userValidation;
    }

    public function getUserValidationDate(): ?\DateTimeInterface
    {
        return $this->userValidationDate;
    }

    public function getUserSuspended(): ?bool
    {
        return $this->userSuspended;
    }

    public function getUserSuspendedDate(): ?\DateTimeInterface
    {
        return $this->userSuspendedDate;
    }

    public function getUserDeleted(): ?bool
    {
        return $this->userDeleted;
    }

    public function getUserDeletedDate(): ?\DateTimeInterface
    {
        return $this->userDeletedDate;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set the value of lastName
     *
     * @return  self
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Set the value of firstName
     *
     * @return  self
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set the value of birthDate
     *
     * @return  self
     */
    public function setBirthDate(?\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Set the value of createDate
     *
     * @return  self
     */
    public function setCreateDate(\DateTimeInterface $createDate): self
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Set the value of userValidation
     *
     * @return  self
     */
    public function setUserValidation(bool $userValidation): self
    {
        $this->userValidation = $userValidation;

        return $this;
    }

    /**
     * Set the value of userValidationDate
     *
     * @return  self
     */
    public function setUserValidationDate(?\DateTimeInterface $userValidationDate): self
    {
        $this->userValidationDate = $userValidationDate;

        return $this;
    }

    /**
     * Set the value of userSuspended
     *
     * @return  self
     */
    public function setUserSuspended(bool $userSuspended)
    {
        $this->userSuspended = $userSuspended;

        return $this;
    }

    /**
     * Set the value of userSuspendedDate
     *
     * @return  self
     */
    public function setUserSuspendedDate(?\DateTimeInterface $userSuspendedDate): self
    {
/*
        $minSuspendedDate = new DateTime();

        if ($userSuspendedDate > $minSuspendedDate && isset($userSuspendedDate)) {
            throw new InvalidArgumentException('Date de suspension invalide');
        }*/
        $this->userSuspendedDate = $userSuspendedDate;

        return $this;
    }

    /**
     * Set the value of userDeleted
     *
     * @return  self
     */
    public function setUserDeleted(bool $userDeleted)
    {
        $this->userDeleted = $userDeleted;

        return $this;
    }

    /**
     * Set the value of userDeletedDate
     *
     * @return  self
     */
    public function setUserDeletedDate(?\DateTimeInterface $userDeletedDate): self
    {
        $this->userDeletedDate = $userDeletedDate;

        return $this;
    }

    /**
     * Entity builder with requested parameters
     *
     * @return  self
     */
    public static function build(
        ?string $firstName,
        ?string $lastName,
        ?string $email,
        ?string $password,
        ?string $birthDate
    ): User {
        $user = new User();
        $firstName ? $user->setFirstName($firstName) : null ;
        $lastName ? $user->setLastName($lastName) : null ;
        $email ? $user->setEmail($email) : null ;
        $password ? $user->setPassword($password) : null ;
        $birthDate ? $user->setBirthDate(DateTime::createFromFormat('Y-m-d', $birthDate)) : null ;

        $user->setCreateDate(new DateTime())
            ->setUserValidation(false)
            ->setUserSuspended(true)
            ->setUserDeleted(false);

        return $user;
    }


    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
