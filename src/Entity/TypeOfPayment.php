<?php

namespace App\Entity;

use App\Repository\TypeOfPaymentRepository;
use Doctrine\ORM\Mapping as ORM;

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
     *  message="type de paiement vide",
     *  groups={"typeOfPayment"}
     * )
     * @Assert\Regex(
     *  pattern =  "/^[a-zA-ZÀ-ÿ -]{1,30}$/",
     *  message=" : {{ value }} incorrect",
     *  groups={"typeOfPayment"}
     * )
     */
    private string $typeOfPayment;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getTypeOfPayment()
    {
        return $this->typeOfPayment;
    }


    public function setRealTypeOfPayment(): void
    {
        $this->typeOfPayment = 'Réel';
    }

    public function setVirtualTypeOfPayment(): void
    {
        $this->typeOfPayment = 'Virtuel';
    }
}
