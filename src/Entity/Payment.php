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
    public const PAYMENT_STATUS = [
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
     *  value="+1 minutes",
     *  groups={"datePayment", "payment"}
     * )
     */
    protected DateTimeInterface $datePayment;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(
     *      message="Sum is empty",
     *      groups={"sum", "payment"}
     * )
     * @Assert\Type(
     *      type="integer",
     *      message="{{ value }} n'est pas du type {{ type }}",
     *      groups={"sum", "payment"}
     * )
     * @Assert\Positive(
     *      message="Sum must be positive",
     *      groups={"sum", "payment"}
     * )
     */
    private int $sum;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(
     *      message="Payment status is empty",
     *      groups={"paymentStatus", "payment"}
     * )
     * @Assert\Choice(
     *      choices={0,1,2},
     *      message="Status incorrect",
     *      groups={"paymentStatus", "payment"}
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
     * @var Collection<int, Item>|null
     */
    private ?Collection $items;

    /**
     * @ORM\ManyToOne(targetEntity=Wallet::class, inversedBy="payments")
     * @Assert\NotNull(
     *      message="Wallet is empty",
     * )
     * @Assert\Valid
     */
    private Wallet $wallet;

    /**
     * @ORM\ManyToOne(targetEntity=WebsiteWallet::class, inversedBy="payments")
     */
    private ?WebsiteWallet $websiteWallet;


    public function __construct(float $sum)
    {
        $this->datePayment = new DateTime();
        $this->sum = (int) ($sum * 100);
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

    public function getSum(): float
    {
        return $this->sum / 100;
    }

    /******************************** paymentStatus ****************************** */

    public function getPaymentStatusId(): ?int
    {
        return $this->paymentStatusId;
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

    public function setTypeOfPayment(TypeOfPayment $typeOfPayment): self
    {
        $this->typeOfPayment = $typeOfPayment;

        return $this;
    }

    /**
     * @return Collection<int, Item>|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    /**
     * @param Collection<int, Item>|Item[] $items
     * @return $this
     */
    public function setItems(Collection $items): self
    {
        $this->items = $items;

        return $this;
    }

    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    public function setWallet(Wallet $wallet): self
    {
        $this->wallet = $wallet;

        return $this;
    }

    public function getWebsiteWallet(): ?WebsiteWallet
    {
        return $this->websiteWallet;
    }

    public function setWebsiteWallet(?WebsiteWallet $websiteWallet): self
    {
        $this->websiteWallet = $websiteWallet;

        return $this;
    }
}
