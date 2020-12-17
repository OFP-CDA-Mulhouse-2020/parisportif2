<?php

namespace App\Entity;

use App\Repository\PaymentStatusRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PaymentStatusRepository::class)
 */
class PaymentStatus
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *      message="Status vide",
     * )
     * @Assert\Type(
     *     type="string",
     *     message="Format incorrect"
     * )
     * @Assert\Regex(
     *  pattern =  "/^[a-zA-Z0-9À-ÿ '-]{2,30}$/",
     *  message="Format status incorrect, 2 caractères minimum, 20 maximum",
     * )
     */
    private string $paymentStatus;





    public function getId(): ?int
    {
        return $this->id;
    }


    public function getPaymentStatus(): ?string
    {
        return $this->paymentStatus;
    }


    public function setPaymentStatus(string $paymentStatus): self
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }
}
