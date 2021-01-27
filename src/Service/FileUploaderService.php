<?php

namespace App\Service;

use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploaderService
{
    private string $targetDirectory;
    private Filesystem $filesystem;

    public function __construct(string $targetDirectory, Filesystem $filesystem)
    {
        $this->targetDirectory = $targetDirectory;
        $this->filesystem = $filesystem;
    }

    public function upload(UploadedFile $file): string
    {
        // On génère un nouveau nom de fichier
        $newFilename = uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $newFilename);
        } catch (FileException $e) {
            throw new \RuntimeException();
        }

        return $newFilename;
    }

    public function delete(string $file): void
    {
        try {
            $this->filesystem->remove($this->getTargetDirectory() . "/" . $file);
        } catch (IOException $e) {
            throw new \RuntimeException();
        }
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
