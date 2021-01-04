<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CartRepository::class)
 */
class Cart
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Type(
     *     type="integer",
     *     message="{{ value }} n'est pas du type {{ type }}",
     *     groups={"cart"}
     * )
     * @Assert\PositiveOrZero(
     *     message="Sum ne peut pas être négative",
     *     groups={"cart"}
     * )
     */
    private int $sum;

    /**
     * @ORM\OneToMany(targetEntity=Item::class, mappedBy="cart")
     * @Assert\NotNull(
     *     message="Items ne peut pas être vide",
     *     groups={"cart"}
     * )
     * @var Collection<int, Item>|Item[]
     */
    private Collection $items;


    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="cart", cascade={"persist", "remove"})
     */
    private ?User $user;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->sum = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSum(): ?float
    {
        return $this->sum / 100;
    }

    public function setSum(): self
    {
        $this->sum = 0;
        foreach ($this->items as $item) {
            $this->sum += (int) ($item->getAmount() * 100);
        }
        return $this;
    }

    /**
     * @return Collection<int, Item>|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setCart($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getCart() === $this) {
                $item->setCart(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        // set (or unset) the owning side of the relation if necessary
        $newCart = null === $user ? null : $this;
        if ($user->getCart() !== $newCart) {
            $user->setCart($newCart);
        }

        return $this;
    }

    //Peut-être à mettre dans le contrôleur ???
    public function validateCart(): ?Payment
    {
        if (count($this->items) > 0) {
            $this->setSum();
            $payment = new Payment($this->getSum());
            $payment->setItems($this->items);

            $userWallet = $this->user->getWallet();
            $payment->setWallet($userWallet);

            return $payment;
        }
        return null;
    }
}
