<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PlayerStatusRepository::class)
 */
class PlayerStatus
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
     *      message="Statut du joeur vide",
     * )
     * @Assert\Type(
     *     type="string",
     *     message="Format incorrect"
     * )
     * @Assert\Regex(
     *  pattern =  "/^[a-zA-Z0-9À-ÿ '-]{2,30}$/",
     *  message="Format statut du joueur incorrect, 2 caractères minimum, 20 maximum",
     * )
     */
    private string $playerStatus;




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayerStatus(): ?string
    {
        return $this->playerStatus;
    }

    public function setPlayerStatus(?string $playerStatus): self
    {
        $this->playerStatus = $playerStatus;

        return $this;
    }
}
