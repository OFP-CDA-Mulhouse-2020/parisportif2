<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class BetDto
{
    /**
     * @Assert\NotBlank()
     */
    private string $name;
    /**
     * @Assert\NotBlank()
     * @Assert\GreaterThan(value="1")
     */
    private float $odds;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return float
     */
    public function getOdds(): float
    {
        return $this->odds;
    }

    /**
     * @param float $odds
     */
    public function setOdds(float $odds): void
    {
        $this->odds = $odds;
    }


    public static function build(array $listOfOdds): BetDto
    {
        $betDto = new BetDto();
        $betDto->setName($listOfOdds[0]);
        $betDto->setOdds($listOfOdds[1]);

        return $betDto;
    }
}
