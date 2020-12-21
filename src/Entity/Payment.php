<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PaymentRepository::class)
 */
class Payment
{
    private const PAYMENT_STATUS = [
        0 => 'attente de paiement',
        1 => 'paiement rejeté',
        2 => 'paiement accepté',
    ];

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
     *      message="Amount is empty",
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
     * @ORM\Column(type="integer")
     * @Assert\NotNull(
     *      message="Payment status is empty",
     *      groups={"paymentStatus"}
     * )
     * @Assert\Choice(
     *      choices={0,1,2},
     *      message="Status incorrect",
     *      groups={"paymentStatus"}
     * )
     */
    private int $paymentStatusId;

    /**
     * @ORM\ManyToOne(targetEntity=TypeOfPayment::class)
     * @Assert\NotNull(
     *      message="Type of Payment is empty",
     * )
     * @Assert\Valid
     */
    private TypeOfPayment $typeOfPayment;

    /**
     * @ORM\OneToMany(targetEntity=Item::class, mappedBy="payment")
     */
    private ArrayCollection $items;


    public function __construct(float $amount)
    {
        $this->datePayment = new DateTime();
        $this->amount = (int) ($amount * 100);
        $this->paymentStatusId = 0;
        $this->items = new ArrayCollection();
    }

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

    /******************************** amount ****************************** */

    public function getAmount(): float
    {
        return $this->amount / 100;
    }

    /******************************** paymentStatus ****************************** */

    public function getPaymentStatusId(): ?int
    {
        return $this->paymentStatusId;
    }

    public function onGoPayment(): void
    {
        $this->paymentStatusId = 0;
    }

    public function refusePayment(): void
    {
        $this->paymentStatusId = 1;
    }

    public function acceptPayment(): void
    {
        $this->paymentStatusId = 2;
    }

    public function getTypeOfPayment(): ?TypeOfPayment
    {
        return $this->typeOfPayment;
    }

    public function setTypeOfPayment(?TypeOfPayment $typeOfPayment): self
    {
        $this->typeOfPayment = $typeOfPayment;

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setPayment($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getPayment() === $this) {
                $item->setPayment(null);
            }
        }

        return $this;
    }
}
