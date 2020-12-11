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
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Iban vide",
     *  groups={"ibanCode", "bankInfo"}
     * )
     * @Assert\Iban(
     *  message="Ce n'est pas un IBAN valide (IBAN).",
     *  groups={"ibanCode","bankInfo"}
     * )
     */
    private string $ibanCode;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="code bic vide",
     *  groups={"bicCode", "bankInfo"}
     * )
     * @Assert\Bic(
     *  message="Code BIC est invalide.",
     *  groups={"bicCode","bankInfo"}
     * )
     */
    private string $bicCode;


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
}
