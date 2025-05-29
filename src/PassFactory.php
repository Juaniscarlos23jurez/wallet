<?php

namespace AppleWallet\Passbook;

use AppleWallet\Passbook\Exception\PassException;
use AppleWallet\Passbook\Pass\Pass;
use ZipArchive;

class PassFactory
{
    protected string $passTypeIdentifier;
    protected string $teamIdentifier;
    protected string $organizationName;
    protected string $p12File;
    protected string $p12Password;
    protected string $wwdrFile;
    protected string $outputPath;
    protected string $tempPath;

    public function __construct(
        string $passTypeIdentifier,
        string $teamIdentifier,
        string $organizationName,
        string $p12File,
        string $p12Password,
        string $wwdrFile
    ) {
        $this->passTypeIdentifier = $passTypeIdentifier;
        $this->teamIdentifier = $teamIdentifier;
        $this->organizationName = $organizationName;
        $this->p12File = $p12File;
        $this->p12Password = $p12Password;
        $this->wwdrFile = $wwdrFile;
        $this->tempPath = sys_get_temp_dir() . '/passbook_' . uniqid();
    }

    public function setOutputPath(string $outputPath): self
    {
        $this->outputPath = $outputPath;
        return $this;
    }

    public function getOutputPath(): string
    {
        return $this->outputPath;
    }

    public function package(Pass $pass): string
    {
        if (!file_exists($this->p12File)) {
            throw new PassException('Certificate file does not exist');
        }

        if (!file_exists($this->wwdrFile)) {
            throw new PassException('WWDR certificate file does not exist');
        }

        // Create temporary directory
        if (!mkdir($this->tempPath, 0777, true)) {
            throw new PassException('Could not create temporary directory');
        }

        try {
            // Set pass properties
            $pass->setPassTypeIdentifier($this->passTypeIdentifier);
            $pass->setTeamIdentifier($this->teamIdentifier);
            $pass->setOrganizationName($this->organizationName);

            // Create pass.json
            $this->createPassJson($pass);

            // Copy images
            $this->copyImages($pass);

            // Create manifest.json
            $this->createManifestJson();

            // Create signature
            $this->createSignature();

            // Create .pkpass file
            $pkpassFile = $this->createPkpassFile($pass);

            return $pkpassFile;
        } finally {
            // Clean up temporary directory
            $this->cleanup();
        }
    }

    protected function createPassJson(Pass $pass): void
    {
        $json = json_encode($pass->toArray(), JSON_PRETTY_PRINT);
        if ($json === false) {
            throw new PassException('Could not encode pass.json');
        }

        if (file_put_contents($this->tempPath . '/pass.json', $json) === false) {
            throw new PassException('Could not write pass.json');
        }
    }

    protected function copyImages(Pass $pass): void
    {
        foreach ($pass->getImages() as $image) {
            $sourcePath = $image->getPath();
            $targetPath = $this->tempPath . '/' . $image->getFilename();

            if (!copy($sourcePath, $targetPath)) {
                throw new PassException(sprintf('Could not copy image %s', $sourcePath));
            }
        }
    }

    protected function createManifestJson(): void
    {
        $manifest = [];
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->tempPath),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($files as $file) {
            if ($file->isFile()) {
                $relativePath = str_replace($this->tempPath . '/', '', $file->getPathname());
                $manifest[$relativePath] = sha1_file($file->getPathname());
            }
        }

        $json = json_encode($manifest, JSON_PRETTY_PRINT);
        if ($json === false) {
            throw new PassException('Could not encode manifest.json');
        }

        if (file_put_contents($this->tempPath . '/manifest.json', $json) === false) {
            throw new PassException('Could not write manifest.json');
        }
    }

    protected function createSignature(): void
    {
        $manifestPath = $this->tempPath . '/manifest.json';
        $signaturePath = $this->tempPath . '/signature';

        $certificate = file_get_contents($this->p12File);
        if ($certificate === false) {
            throw new PassException('Could not read certificate file');
        }

        if (!openssl_pkcs12_read($certificate, $certificates, $this->p12Password)) {
            throw new PassException('Could not read certificate');
        }

        $data = file_get_contents($manifestPath);
        if ($data === false) {
            throw new PassException('Could not read manifest.json');
        }

        $signature = '';
        if (!openssl_pkcs7_sign(
            $manifestPath,
            $signaturePath,
            $certificates['cert'],
            $certificates['pkey'],
            [],
            PKCS7_BINARY | PKCS7_DETACHED,
            $this->wwdrFile
        )) {
            throw new PassException('Could not create signature');
        }

        $signature = file_get_contents($signaturePath);
        if ($signature === false) {
            throw new PassException('Could not read signature file');
        }

        // Remove the temporary signature file
        unlink($signaturePath);

        // Write the signature
        if (file_put_contents($signaturePath, $signature) === false) {
            throw new PassException('Could not write signature file');
        }
    }

    protected function createPkpassFile(Pass $pass): string
    {
        $zip = new ZipArchive();
        $pkpassFile = $this->outputPath . '/' . $pass->getSerialNumber() . '.pkpass';

        if ($zip->open($pkpassFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new PassException('Could not create .pkpass file');
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->tempPath),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($files as $file) {
            if ($file->isFile()) {
                $relativePath = str_replace($this->tempPath . '/', '', $file->getPathname());
                $zip->addFile($file->getPathname(), $relativePath);
            }
        }

        $zip->close();

        return $pkpassFile;
    }

    protected function cleanup(): void
    {
        if (is_dir($this->tempPath)) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($this->tempPath, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );

            foreach ($files as $file) {
                if ($file->isDir()) {
                    rmdir($file->getPathname());
                } else {
                    unlink($file->getPathname());
                }
            }

            rmdir($this->tempPath);
        }
    }
} 