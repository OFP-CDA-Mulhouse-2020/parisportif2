<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class DatabaseService
{
    private object $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function saveToDatabase(object $data): void
    {
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }
}
