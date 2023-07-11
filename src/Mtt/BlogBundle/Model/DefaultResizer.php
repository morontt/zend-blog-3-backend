<?php

namespace Mtt\BlogBundle\Model;

use Imagick;
use ImagickException;

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
