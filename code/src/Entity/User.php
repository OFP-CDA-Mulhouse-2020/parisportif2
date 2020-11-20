<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Exception;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User
{
    private const PATTERN_MAIL = '/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/';
    private const PATTERN_PASS = '/^[a-zA-Z0-9_]{6,12}$/';
    private const PATTERN_LOGIN = '/^[a-zA-Z0-9À-ÿ_.-]{2,16}$/';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mail;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     * @param int $id
     * @throws Exception
     * @return  self
     */
    public function setId(int $id): self
    {
        if (! is_int($id) || $id < 0) {
            throw new Exception('Id invalide');
        }
        $this->id = $id;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $pattern = self::PATTERN_LOGIN;
        if (! preg_match($pattern, $login)) {
            throw new Exception('login est invalide');
        }
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $pattern = self::PATTERN_PASS;
        if (! preg_match($pattern, $password)) {
            throw new Exception('password est invalide');
        }
        $this->password = $password;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $pattern = self::PATTERN_MAIL;
        if (! preg_match($pattern, $mail)) {
            throw new Exception('mail est invalide');
        }
        $this->mail = $mail;

        return $this;
    }
}
