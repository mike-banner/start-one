<?php

namespace App\Tests\Service;

use App\Service\FileUploader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\String\UnicodeString;

class FileUploaderTest extends TestCase
{
    public function testUploadReturnsRelativePathAndMovesFile(): void
    {
        // Arrange
        $slugger = $this->createMock(SluggerInterface::class);
        $slugger->method('slug')->willReturn(new UnicodeString('safe-filename'));

        $baseTargetDir = sys_get_temp_dir() . '/upload_test';

        $fileUploader = new FileUploader($baseTargetDir, $slugger);

        $uploadedFile = $this->createMock(UploadedFile::class);
        $uploadedFile->method('getClientOriginalName')->willReturn('fichier test.jpg');
        $uploadedFile->method('guessExtension')->willReturn('jpg');

        $uploadedFile->expects($this->once())
            ->method('move')
            ->with(
                $this->stringContains($baseTargetDir . '/images_produit/1234'),
                $this->matchesRegularExpression('/^safe-filename-[a-z0-9]+\.jpg$/')
            );

        // Act
        $result = $fileUploader->upload($uploadedFile, 'images_produit', '1234');

        // Assert
        $this->assertMatchesRegularExpression(
            '/^images_produit\/1234\/safe-filename-[a-z0-9]+\.jpg$/',
            $result
        );

        // Clean up
        $this->deleteDirectory($baseTargetDir);
    }

    private function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = "$dir/$file";
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }
}
