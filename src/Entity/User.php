<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *  message="Nom vide",
     *  groups={"username", "register"}
     * )
     * @Assert\Regex(
     *  pattern =  "/^[a-zA-ZÀ-ÿ '-]{1,30}$/",
     *  message="Nom incorrect",
     *  groups={"username", "register"}
     * )
     */
    private string $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *  message="Prénom vide",
     *  groups={"username", "register"}
     * )
     * @Assert\Regex(
     *  pattern =  "/^[a-zA-ZÀ-ÿ '-]{1,30}$/",
     *  message="Prénom incorrect",
     *  groups={"username", "register"}
     * )
     */
    private string $firstName;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(
     *  message="Email vide",
     *  groups={"email","login", "register"}
     * )
     * @Assert\Email(
     *  message="Format email incorrect",
     *  groups={"email","login", "register"}
     * )
     */
    private string $email;

    /**
     * @Assert\NotBlank(
     *  message="Password vide",
     *  groups={"password","login", "register"}
     * )
     * @Assert\Regex(
     *  pattern =  "/^(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,16}$/",
     *  message="Format password incorrect, 1 Majuscule, 1 Chiffre, 8 caractères minimum",
     *  groups={"password","login", "register"}
     * )
     */
    private string $plainPassword;

    /**
     * @var string the hashed password
     * @ORM\Column(type="string", length=255)
     */
    private string $password;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(
     *  message="Date de naissance vide",
     *  groups={"birthDate", "register"}
     * )
     * @Assert\LessThanOrEqual(value="-18 years",
     *  message="Vous n'avez pas 18 ans minimum",
     *  groups={"birthDate", "register"}
     * )
     */
    private ?DateTimeInterface $birthDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(
     *  message="Date de création vide",
     *  groups={"createAt"}
     * )
     * @Assert\LessThanOrEqual(
     *  value="+1 minutes",
     *  message="Date de création incorrecte",
     *  groups={"createAt"}
     * )
     */
    private DateTimeInterface $createAt;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotNull(
     *  message="Status validation null",
     *  groups={"active"}
     * )
     * @Assert\Type(
     *  type="bool",
     *  message="{{ value }} n'est pas du type {{ type }}",
     *  groups={"active"}
     * )
     */
    private bool $active = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\LessThanOrEqual(
     *  value="+1 minutes",
     *  message="Date de validation incorrecte : {{ value }}",
     *  groups={"active"}
     * )
     */
    private ?DateTimeInterface $activeAt;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotNull(
     *  message="Status suspendu null",
     *  groups={"suspend"}
     * )
     * @Assert\Type(
     *  type="bool",
     *  message="{{ value }} n'est pas du type {{ type }}",
     *  groups={"suspend"}
     * )
     */
    private bool $suspended = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\LessThanOrEqual(
     *  value="+1 minutes",
     *  message="Date de suspension incorrecte : {{ value }}",
     *  groups={"suspend"}
     * )
     */
    private ?DateTimeInterface $suspendedAt;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotNull(
     *  message="Status supprimé null",
     *  groups={"delete"}
     * )
     * @Assert\Type(
     *  type="bool",
     *  message="{{ value }} n'est pas du type {{ type }}",
     *  groups={"delete"}
     * )
     */
    private bool $deleted = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\LessThanOrEqual(
     *  value="+1 minutes",
     *  message="Date de suppression incorrecte",
     *  groups={"delete"}
     * )
     */
    private ?DateTimeInterface $deletedAt;

    /**
     * @ORM\OneToOne(targetEntity=Address::class, cascade={"persist", "remove"})
     * @Assert\Valid(groups={"address"})
     */
    private ?Address $address;

    /**
     * @ORM\OneToOne(targetEntity=BankAccount::class, cascade={"persist", "remove"})
     * @Assert\Valid(groups={"bankAccount"})
     */
    private ?BankAccount $bankAccount;

    /**
     * @ORM\OneToOne(targetEntity=Cart::class, inversedBy="user", cascade={"persist", "remove"})
     * @Assert\Valid(groups={"cart"})
     */
    private ?Cart $cart;

    /**
     * @ORM\OneToOne(targetEntity=Wallet::class, cascade={"persist", "remove"})
     * @Assert\Valid(groups={"wallet"})
     */
    private ?Wallet $wallet;

    /**
     * @ORM\OneToOne(targetEntity=CardIdFile::class, cascade={"persist", "remove"})
     * @Assert\Valid(groups={"cardIdFile"})
     */
    private ?CardIdFile $cardIdFile;





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

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function getBirthDate(): ?DateTimeInterface
    {
        return $this->birthDate;
    }

    public function getCreateAt(): ?DateTimeInterface
    {
        return $this->createAt;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function getActiveAt(): ?DateTimeInterface
    {
        return $this->activeAt;
    }

    public function isSuspended(): ?bool
    {
        return $this->suspended;
    }

    public function getSuspendedAt(): ?DateTimeInterface
    {
        return $this->suspendedAt;
    }

    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function getDeletedAt(): ?DateTimeInterface
    {
        return $this->deletedAt;
    }

    /**
     * Set the value of lastName
     *
     * @param string $lastName
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
     * @param string $firstName
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
     * @param string $email
     * @return  self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set the value of plainPassword
     *
     * @param string $plainPassword
     * @return  self
     */
    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * Set the value of birthDate
     *
     * @param DateTimeInterface|null $birthDate
     * @return  self
     */
    public function setBirthDate(?DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Set the value of createAt
     *
     * @param DateTimeInterface $createAt
     * @return  self
     */
    public function setCreateAt(DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Set user valid
     *
     * @return  self
     */
    public function activate(): self
    {
        $this->active = true;
        $this->activeAt = new DateTime();

        return $this;
    }

    /**
     * Set user not valid
     *
     * @return  self
     */
    public function deactivate(): self
    {
        $this->active = false;
        $this->activeAt = null;

        return $this;
    }

    /**
     * Set user suspended
     *
     * @return  self
     */
    public function suspend(): self
    {
        $this->suspended = true;
        $this->suspendedAt = new DateTime();

        return $this;
    }

    /**
     * Set user not suspended
     *
     * @return  self
     */
    public function unsuspended(): self
    {
        $this->suspended = false;
        $this->suspendedAt = null;

        return $this;
    }

    /**
     * Set user delete
     *
     * @return  self
     */
    public function delete(): self
    {
        $this->deleted = true;
        $this->deletedAt = new DateTime();

        return $this;
    }

    /**
     * Set user not delete
     *
     * @return  self
     */
    public function undelete(): self
    {
        $this->deleted = false;
        $this->deletedAt = null;

        return $this;
    }

    /**
     * Entity builder with the requested parameters
     *
     * @param string|null $firstName
     * @param string|null $lastName
     * @param string|null $email
     * @param string|null $plainPassword
     * @param string|null $birthDate
     * @return  self
     */
    public static function build(
        ?string $firstName,
        ?string $lastName,
        ?string $email,
        ?string $plainPassword,
        ?string $birthDate
    ): User {
        $user = new User();
        $firstName ? $user->setFirstName($firstName) : null;
        $lastName ? $user->setLastName($lastName) : null;
        $email ? $user->setEmail($email) : null;
        $plainPassword ? $user->setPlainPassword($plainPassword) : null;
        $birthDate ? $user->setBirthDate(DateTime::createFromFormat('Y-m-d', $birthDate)) : null;

        $user->setCreateAt(new DateTime());


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
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->password = null;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getBankAccount(): ?BankAccount
    {
        return $this->bankAccount;
    }

    public function setBankAccount(?BankAccount $bankAccount): self
    {
        $this->bankAccount = $bankAccount;

        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }

    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    public function setWallet(?Wallet $wallet): self
    {
        $this->wallet = $wallet;

        return $this;
    }

    public function getCardIdFile(): ?CardIdFile
    {
        return $this->cardIdFile;
    }

    public function setCardIdFile(?CardIdFile $cardIdFile): self
    {
        $this->cardIdFile = $cardIdFile;

        return $this;
    }
}
