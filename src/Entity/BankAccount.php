<?php

namespace App\Entity;

use App\Repository\BankAccountRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BankAccountRepository::class)
 */
class BankAccount
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Iban vide",
     *  groups={"ibanCode", "bankAccount"}
     * )
     * @Assert\Iban(
     *  message="Cet IBAN n'est pas valide.",
     *  groups={"ibanCode","bankAccount"}
     * )
     */
    private string $ibanCode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="code Bic vide",
     *  groups={"bicCode", "bankAccount"}
     * )
     * @Assert\Bic(
     *  message="Ce code BIC n'est pas valide.",
     *  groups={"bicCode","bankAccount"}
     * )
     */
    private string $bicCode;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="bankAccount", cascade={"persist", "remove"})
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
    /******************************** ibanCode ****************************** */

    public function getIbanCode(): ?string
    {
        return $this->ibanCode;
    }

    public function setIbanCode(string $ibanCode): self
    {
        $this->ibanCode = $ibanCode;

        return $this;
    }

    /******************************** bicCode ****************************** */

    public function getBicCode(): string
    {
        return $this->bicCode;
    }

    public function setBicCode(string $bicCode): self
    {
        $this->bicCode = $bicCode;

        return $this;
    }

    /**
     * Entity builder with requested parameters
     *
     * @return  self
     */
    public static function build(
        ?string $ibanCode,
        ?string $bicCode
    ): BankAccount {
        $bankAccount = new BankAccount();
        $ibanCode ? $bankAccount->setIbanCode($ibanCode) : null;
        $bicCode ? $bankAccount->setBicCode($bicCode) : null;

        return $bankAccount;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        // set (or unset) the owning side of the relation if necessary
        $newBankAccount = null === $user ? null : $this;
        if ($user->getBankAccount() !== $newBankAccount) {
            $user->setBankAccount($newBankAccount);
        }

        return $this;
    }
}
