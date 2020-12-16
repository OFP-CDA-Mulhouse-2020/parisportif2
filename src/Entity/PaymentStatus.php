<?php

namespace App\Entity;

use App\Repository\PaymentStatusRepository;
use Doctrine\ORM\Mapping as ORM;

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


    private $paymentStatus;


    public function __construct()
    {
        $this->paymentStatus = "Paiement en cours";
    }




    public function getId(): ?int
    {
        return $this->id;
    }


    public function getPaymentStatus()
    {
        return $this->paymentStatus;
    }


    public function onGoPayment(): void
    {
        $this->paymentStatus = 'Paiement en cours';
    }


    public function refusePayment(): void
    {
        $this->paymentStatus = 'Paiement refusé';
    }


    public function acceptPayment(): void
    {
        $this->paymentStatus = 'Paiement accepté';
    }
}
