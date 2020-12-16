<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PaymentRepository::class)
 */
class Payment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;


    /**
     * @ORM\Column(type="string", length=255)
     *
     *@Assert\NotBlank(message="Intutilé du paiement vide !",
     *  groups={"paymentName", "payment"}
     * )
     * @Assert\Regex(
     *  pattern =  "/^[a-zA-ZÀ-ÿ '-]{2,30}$/",
     *  message="Payment name is incorrect",
     *  groups={"paymentName", "payment"}
     * )
     */
    private string $paymentName;


    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(
     *  message="La date de paiement est vide",
     *  groups={"datePayment", "payment"}
     * )
     *
     * @Assert\LessThanOrEqual(
     *  value="+1 hours",
     *  groups={"datePayment", "payment"}
     * )
     */
    protected DateTimeInterface $datePayment;



    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(
     *      message="Amount is incorrect",
     * )
     * @Assert\Type(
     *      type="integer",
     *      message="{{ value }} n'est pas du type {{ type }}",
     * )
     * @Assert\Positive(
     *      message="Amount must be positive",
     * groups={"amount"}
     * )
     */
    private int $amount;



    /**
     * @ORM\Column(type="integer", length=255)
     * @Assert\NotBlank(
     *  groups={"paymentStatus","payment"}
     * )
     *
     * @Assert\type("integer")
     *
     * @Assert\Choice({1, 2, 3})
     *
     */
    private int $paymentStatus;




    public function __construct(float $amount)
    {
        $this->datePayment = new DateTime();

        $this->paymentStatus = 1;

        $this->amount = (int) ($amount * 100);
    }





    /******************************** id ****************************** */


    public function getId(): ?int
    {
        return $this->id;
    }


    /******************************** paymentName ****************************** */


    public function getPaymentName(): string
    {
        return $this->paymentName;
    }


    public function setPaymentName(string $paymentName): self
    {
        $this->paymentName = $paymentName;

        return $this;
    }


    /******************************** datePayment ****************************** */


    public function getDatePayment(): DateTimeInterface
    {
        return $this->datePayment;
    }


    public function setDatePayment(DateTimeInterface $datePayment): void
    {
        $this->datePayment = $datePayment;
    }




    /******************************** amount ****************************** */


    public function getAmount(): float
    {
        return $this->amount / 100;
    }


    /******************************** paymentStatus ****************************** */


    public function getPaymentStatus(): int
    {
        return $this->paymentStatus;
    }



    public function setPaymentStatus($paymentStatus): void
    {
        $this->paymentStatus = $paymentStatus;
    }


    public function onGoPayment(): void
    {
        $this->paymentStatus = 1;
    }


    public function refusePayment(): void
    {
        $this->paymentStatus = 2;
    }


    public function acceptPayment(): void
    {
        $this->paymentStatus = 3;
    }
}
