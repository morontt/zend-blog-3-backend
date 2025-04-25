<?php

namespace App\Model;

interface ResizerInterface
{
    public function resize(string $filePath, string $newFilePath, int $width, int $height);
}
