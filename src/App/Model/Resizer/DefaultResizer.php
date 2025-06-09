<?php

namespace App\Model\Resizer;

use Imagick;
use ImagickException;

class DefaultResizer extends CommonResizer
{
    /**
     * @throws ImagickException
     */
    public function resize(string $filePath, string $newFilePath, int $width, int $height): void
    {
        $image = new Imagick($filePath);
        $this->orientate($image);
        $image->thumbnailImage($width, $height);

        $image->writeImage($newFilePath);
        $image->clear();
    }
}
