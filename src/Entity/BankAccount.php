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
    private int $id;

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
     * @ORM\OneToOne(targetEntity=BankAccountFile::class, cascade={"persist", "remove"})
     * @Assert\Valid(groups={"bankAccountFile" , "bankAccount"})
     */
    private ?BankAccountFile $bankAccountFile;

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

    public function getBankAccountFile(): ?BankAccountFile
    {
        return $this->bankAccountFile;
    }

    public function setBankAccountFile(?BankAccountFile $bankAccountFile): self
    {
        $this->bankAccountFile = $bankAccountFile;

        return $this;
    }
}
