<?php

namespace App\FormHandler;

use App\Entity\CardIdFile;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EditIdentityHandler
{
    private UserRepository $userRepository;
    private UserPasswordEncoderInterface $passwordEncoder;
    private EntityManagerInterface $entityManager;

    public function __construct(
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager
    ) {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }

    public function process(FormInterface $identityForm, User $user, string $uploadDir): void
    {
        //On récupère le fichier transmit
        $file = $identityForm->get('justificatif')->getData();
        if ($file) {
            // On récupère le nom du fichier
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            // On génère un nouveau nom de fichier
            $newFilename = uniqid() . '.' . $file->guessExtension();

            try {
                //Copie du fichier sur le serveur
                $file->move($uploadDir, $newFilename);
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
                throw new FileException();
            }
            // Stockage du fichier dans la base de données
            $idCard = new CardIdFile();
            //mise à jour du nom du fichier
            $idCard->setName($newFilename);
            $user->setCardIdFile($idCard);
        } else {
            throw new \LogicException();
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
