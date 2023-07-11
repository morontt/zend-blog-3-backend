<?php

namespace Mtt\BlogBundle\Model;

use Imagick;
use ImagickException;

class JpegResizer implements ResizerInterface
{
    /**
     * @throws ImagickException
     */
    public function resize(string $filePath, string $newFilePath, int $width, int $height)
    {
        $image = new Imagick($filePath);
        $image->stripImage();

        $image->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 1);

        $format = 'jpeg';
        $image->setFormat($format);
        $image->setImageFormat($format);

        $image->setCompression(Imagick::COMPRESSION_JPEG);
        $image->setImageCompression(Imagick::COMPRESSION_JPEG);
        $image->setCompressionQuality(75);
        $image->setImageCompressionQuality(75);

        $image->writeImage($newFilePath);
        $image->clear();
    }
}
