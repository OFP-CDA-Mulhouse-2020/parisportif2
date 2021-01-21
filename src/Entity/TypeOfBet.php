<?php

namespace App\Entity;

use App\Repository\TypeOfBetRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TypeOfBetRepository::class)
 */
class TypeOfBet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *      message="Type of Bet vide",
     * )
     * @Assert\Type(
     *     type="string",
     *     message="Format incorrect"
     * )
     * @Assert\Regex(
     *  pattern =  "/^[a-zA-Z0-9À-ÿ '-]{2,30}$/",
     *  message="Format Type Of Bet incorrect, 2 caractères minimum, 20 maximum",
     * )
     */
    private string $betType;

    public function __toString()
    {
        return $this->getId() . ' - ' . $this->getBetType();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBetType(): ?string
    {
        return $this->betType;
    }

    public function setBetType(string $betType): self
    {
        $this->betType = $betType;

        return $this;
    }
}
