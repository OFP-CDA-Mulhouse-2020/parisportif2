<?php

namespace App\Entity;

use App\Repository\BankAccountFileRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BankAccountFileRepository::class)
 */
class BankAccountFile
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *  message="Nom vide",
     *  groups={"bankAccountFile"}
     * )
     * @Assert\Regex(
     *  pattern = "/([a-zA-Z0-9\s_\\.\-\(\):])+(.png|.jpeg|.jpg|.pdf)$/",
     *  message="BankAccountFile : {{ value }} incorrect",
     *  groups={"bankAccountFile"}
     * )
     */
    private string $name;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\Type(
     *  type="bool",
     *  message="{{ value }} n'est pas du type {{ type }}",
     *  groups={"bankAccountFile"}
     * )
     */
    private bool $valid = false;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="bankAccountFile", cascade={"persist", "remove"})
     */
    private ?User $user;

    public function getFullName(): string
    {
        return  $this->user->getId()  . ' - ' . $this->user->getLastName() . ' ' . $this->user->getFirstName();
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

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(bool $valid): self
    {
        $this->valid = $valid;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        // set (or unset) the owning side of the relation if necessary
        $newBankAccountFile = null === $user ? null : $this;
        if ($user->getBankAccountFile() !== $newBankAccountFile) {
            $user->setBankAccountFile($newBankAccountFile);
        }

        return $this;
    }
}
