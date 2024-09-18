<?php

namespace App\Model\Resizer;

use Imagick;
use ImagickException;
use App\Model\ResizerInterface;

class DefaultResizer implements ResizerInterface
{
    /**
     * @throws ImagickException
     */
    public function resize(string $filePath, string $newFilePath, int $width, int $height)
    {
        $image = new Imagick($filePath);
        $image->thumbnailImage($width, $height);

        $image->writeImage($newFilePath);
        $image->clear();
    }
}
