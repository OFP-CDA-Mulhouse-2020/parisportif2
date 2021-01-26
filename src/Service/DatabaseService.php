<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class DatabaseService
{


    public function saveToDatabase(mixed $data): void
    {
        // $entityManager = $this->getDoctrine()->getManager();
        // $entityManager->persist($data);
        // $entityManager->flush();
    }
}
