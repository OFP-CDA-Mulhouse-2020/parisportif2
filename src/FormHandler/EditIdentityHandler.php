<?php

namespace App\FormHandler;

use _HumbugBoxd1d863f2278d\Symfony\Component\Console\Exception\LogicException;
use App\Entity\CardIdFile;
use App\Entity\User;
use App\Factory\PaymentFactory;
use App\Repository\TypeOfPaymentRepository;
use App\Repository\UserRepository;
use App\Repository\WalletRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EditIdentityHandler
{
    private UserRepository $userRepository;
    private UserPasswordEncoderInterface $passwordEncoder;
    private EntityManagerInterface $entityManager;
    private ContainerInterface $container;

    public function __construct(
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager,
        ContainerInterface $container
    ) {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
        $this->container = $container;
    }

    public function process(FormInterface $identityForm, User $user): void
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
                $file->move(
                    $this->container->getParameter('files_directory'),
                    $newFilename
                );
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
