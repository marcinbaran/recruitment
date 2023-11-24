<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileUploader {
    public function upload(UploadedFile $file);
    public function getTargetDirectory();
}