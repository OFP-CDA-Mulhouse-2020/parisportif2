<?php

namespace App\Entity;

use App\Repository\CardIdFileRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CardIdFileRepository::class)
 */
class CardIdFile
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isCardIdValid;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIsCardIdValid(): ?bool
    {
        return $this->isCardIdValid;
    }

    public function setIsCardIdValid(?bool $isCardIdValid): self
    {
        $this->isCardIdValid = $isCardIdValid;

        return $this;
    }
}
