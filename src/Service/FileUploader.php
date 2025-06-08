<?php

// src/Service/FileUploader.php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private string $targetDirectory;
    private SluggerInterface $slugger;

    public function __construct(string $targetDirectory, SluggerInterface $slugger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
    }

    /**
     * @param UploadedFile $file
     * @param string $targetDirectory sous-dossier dans uploads (ex: "images_produit")
     * @param string|null $subfolder sous-dossier optionnel supplémentaire (ex: numéro produit)
     * @return string chemin relatif à /uploads (ex: images_produit/1234/nom-fichier.webp)
     */
    public function upload(UploadedFile $file, string $targetDirectory, ?string $subfolder = null): string
    {
        $directory = $this->targetDirectory . '/' . trim($targetDirectory, '/');
        if ($subfolder) {
            $directory .= '/' . trim($subfolder, '/');
        }

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        $file->move($directory, $fileName);

        $relativePath = trim($targetDirectory, '/');
        if ($subfolder) {
            $relativePath .= '/' . trim($subfolder, '/');
        }

        return $relativePath . '/' . $fileName;
    }
}
