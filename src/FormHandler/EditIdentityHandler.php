<?php

namespace App\FormHandler;

use App\Entity\User;
use App\Factory\CardIdFileFactory;
use App\Service\FileUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class EditIdentityHandler
{
    private EntityManagerInterface $entityManager;
    private FileUploaderService $fileUploader;

    public function __construct(
        EntityManagerInterface $entityManager,
        FileUploaderService $fileUploader
    ) {
        $this->entityManager = $entityManager;
        $this->fileUploader = $fileUploader;
    }

    public function process(FormInterface $identityForm, User $user): void
    {
        //On récupère le fichier transmit
        /** @var UploadedFile|null $file */
        $file = $identityForm->get('justificatif')->getData();
        if ($file) {
            //upload du fichier
            $newFilename = $this->fileUploader->upload($file);
            $oldCardIdFile = $user->getCardIdFile();

            //suppression de l'ancien CardId en Database et dans le repertoire Upload
            if ($oldCardIdFile) {
                /** @var string $fileName */
                $fileName = $oldCardIdFile->getName();
                $this->fileUploader->delete($fileName);
                $this->entityManager->remove($oldCardIdFile);
            }
            //création de l'entité CardIdFile
            $cardIdFile = CardIdFileFactory::makeCardIdFile($newFilename);
            //mise à jour du nom du fichier
            $user->setCardIdFile($cardIdFile);
        } else {
            throw new \LogicException();
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
