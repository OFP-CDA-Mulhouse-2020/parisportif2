<?php

namespace App\Entity;

use App\Repository\TypeOfBetRepository;
use Doctrine\ORM\Mapping as ORM;

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
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Type(
     *     type="string",
     *     message="Format incorrect"
     * )
     */
    private string $typeOfBet;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeOfBet(): ?string
    {
        return $this->typeOfBet;
    }

    public function setTypeOfBet(string $typeOfBet): self
    {
        $this->typeOfBet = $typeOfBet;

        return $this;
    }
}
