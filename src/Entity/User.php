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
     * groups={"id", "login"}
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
     * groups={"createAt"}
     * )
     * @Assert\LessThanOrEqual(value="+1 hours",
     * message="Date de création incorrecte : {{ value }}",
     * groups={"createAt"}
     * )
     */
    private DateTimeInterface $createAt;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotNull(message="Status validation null",
     * groups={"valid"}
     * )
     * @Assert\Type(
     * type="bool",
     * message="{{ value }} n'est pas du type {{ type }}",
     * groups={"valid"}
     * )
     */
    private bool $isValid;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\LessThanOrEqual(value="+1 hours",
     * message="Date de validation incorrecte : {{ value }}",
     * groups={"validAt"}
     * )
     */
    private ?\DateTimeInterface $isValidAt;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotNull(message="Status suspendu null",
     * groups={"suspended"}
     * )
     * @Assert\Type(
     * type="bool",
     * message="{{ value }} n'est pas du type {{ type }}",
     * groups={"suspended"}
     * )
     */
    private bool $isSuspended;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\LessThanOrEqual(value="+1 hours",
     * message="Date de suspension incorrecte : {{ value }}",
     * groups={"suspendedAt"}
     * )
     */
    private ?\DateTimeInterface $isSuspendedAt;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotNull(message="Status supprimé null",
     * groups={"deleted"}
     * )
     * @Assert\Type(
     * type="bool",
     * message="{{ value }} n'est pas du type {{ type }}",
     * groups={"deleted"}
     * )
     */
    private bool $isDeleted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\LessThanOrEqual(value="+1 hours",
     * message="Date de suppression incorrecte : {{ value }}",
     * groups={"deletedAt"}
     * )
     */
    private ?\DateTimeInterface $isDeletedAt;

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

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function getIsValid(): ?bool
    {
        return $this->isValid;
    }

    public function getIsValidAt(): ?\DateTimeInterface
    {
        return $this->isValidAt;
    }

    public function getIsSuspended(): ?bool
    {
        return $this->isSuspended;
    }

    public function getIsSuspendedAt(): ?\DateTimeInterface
    {
        return $this->isSuspendedAt;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function getIsDeletedAt(): ?\DateTimeInterface
    {
        return $this->isDeletedAt;
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
     * Set the value of createAt
     *
     * @return  self
     */
    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Set the value of isValid
     *
     * @return  self
     */
    public function setIsValid(bool $isValid): self
    {
        $this->isValid = $isValid;

        return $this;
    }

    /**
     * Set the value of isValidAt
     *
     * @return  self
     */
    public function setIsValidAt(?\DateTimeInterface $isValidAt): self
    {
        $this->isValidAt = $isValidAt;

        return $this;
    }

    /**
     * Set the value of isSuspended
     *
     * @return  self
     */
    public function setIsSuspended(bool $isSuspended): self
    {
        $this->isSuspended = $isSuspended;

        return $this;
    }

    /**
     * Set the value of isSuspendedAt
     *
     * @return  self
     */
    public function setIsSuspendedAt(?\DateTimeInterface $isSuspendedAt): self
    {
        $this->isSuspendedAt = $isSuspendedAt;

        return $this;
    }

    /**
     * Set the value of isDeleted
     *
     * @return  self
     */
    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Set the value of isDeletedAt
     *
     * @return  self
     */
    public function setIsDeletedAt(?\DateTimeInterface $isDeletedAt): self
    {
        $this->isDeletedAt = $isDeletedAt;

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

        $user->setcreateAt(new DateTime())
            ->setIsValid(false)
            ->setIsSuspended(true)
            ->setIsDeleted(false);

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
