<?php

namespace App\Entity;

use App\Repository\TypeOfPaymentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TypeOfPaymentRepository::class)
 */
class TypeOfPayment
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
     *      message="type de paiement vide",
     * )
     * @Assert\Type(
     *     type="string",
     *     message="Format incorrect"
     * )
     * @Assert\Regex(
     *  pattern =  "/^[a-zA-Z0-9À-ÿ '-]{2,30}$/",
     *  message="Format type de paiement incorrect, 2 caractères minimum, 20 maximum",
     * )
     */
    private string $typeOfPayment;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getTypeOfPayment(): ?string
    {
        return $this->typeOfPayment;
    }

    public function setTypeOfPayment(string $typeOfPayment): self
    {
        $this->typeOfPayment = $typeOfPayment;

        return $this;
    }
}
