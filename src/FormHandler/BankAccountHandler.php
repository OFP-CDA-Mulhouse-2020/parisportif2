<?php

namespace App\FormHandler;

use App\Entity\BankAccount;
use App\Entity\User;
use App\Factory\BankAccountFileFactory;
use App\Service\DatabaseService;
use App\Service\FileUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BankAccountHandler
{
    private EntityManagerInterface $entityManager;
    private DatabaseService $databaseService;
    private FileUploaderService $fileUploader;

    public function __construct(
        EntityManagerInterface $entityManager,
        DatabaseService $databaseService,
        FileUploaderService $fileUploader
    ) {
        $this->entityManager = $entityManager;
        $this->databaseService = $databaseService;
        $this->fileUploader = $fileUploader;
    }

    public function process(FormInterface $bankAccountForm, User $user): void
    {

        //on récupère le fichier transmit
        /** @var UploadedFile|null $file */
        $file = $bankAccountForm->get('ribJustificatif')->getData();

        if ($file) {
            //upload du nouveau fichier (rib)
            $newFilename = $this->fileUploader->upload($file);
            // mise en mémoire de l'ancien fichier (rib)
            $oldBankAccountFile = $user->getBankAccountFile();

            // suppression de l'ancien RIB (bankAccountFile) dans la Database et le repertoire Upload
            if ($oldBankAccountFile) {
                /** @var string $fileName */
                $fileName = $oldBankAccountFile->getName();
                $this->fileUploader->delete($fileName);
                $this->entityManager->remove($oldBankAccountFile);
            }
            // Création de l'entité BankAccountFile
            $newBankAccountFile = BankAccountFileFactory::makeBankAccountFile($newFilename);
            // Mise à jour du nom du fichier
            $user->setBankAccountFile($newBankAccountFile);
        } else {
            throw new \LogicException();
        }

        $this->databaseService->saveToDatabase($user);
    }
}
