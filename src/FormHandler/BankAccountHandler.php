<?php

namespace App\FormHandler;

use App\Entity\User;
use App\Factory\BankAccountFileFactory;
use App\Service\FileUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BankAccountHandler
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

    public function process(FormInterface $bankAccountForm, User $user): void
    {
//        $bankAccountData = $bankAccountForm->getData();

        // TODO : Faire l'envoi de la pièce jointe

//        on récupère le fichier transmit
        /** @var UploadedFile|null $file */
        $file = $bankAccountForm->get('ribJustificatif')->getData();

        if ($file) {
            //upload du nouveau fichier (rib)
            $newFilename = $this->fileUploader->upload($file);
            // mise en mémoire de l'ancien fichier (rib)
            $oldBankAccountFile = $user->getBankAccount()->getBankAccountFile();

            // suppression de l'ancien RIB (bankAccountFile) dans la Database et le repertoire Upload
            if ($oldBankAccountFile) {
                $this->fileUploader->delete($oldBankAccountFile->getName());
                $this->entityManager->remove($oldBankAccountFile);
            }
            // Création de l'entité BankAccountFile
            $newBankAccountFile = BankAccountFileFactory::makeBankAccountFile($newFilename);
            // Mise à jour du nom du fichier
            $user->getBankAccount()->setBankAccountFile($newBankAccountFile);
        } else {
            throw new \LogicException();
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
