<?php

namespace App\Model;

interface ImageConverterInterface
{
    public function convert(string $filePath, string $resourcePath): string;
}
