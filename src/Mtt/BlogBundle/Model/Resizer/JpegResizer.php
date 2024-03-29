<?php

namespace Mtt\BlogBundle\Model\Resizer;

use Imagick;
use ImagickException;
use Mtt\BlogBundle\Model\ResizerInterface;

class JpegResizer implements ResizerInterface
{
    use DebugAnnotation;

    /**
     * @throws ImagickException
     */
    public function resize(string $filePath, string $newFilePath, int $width, int $height)
    {
        $image = new Imagick($filePath);
        $image->stripImage();

        $image->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 1);

        $image->setFormat($this->getFormat());
        $image->setImageFormat($this->getFormat());

        $image->setCompression(Imagick::COMPRESSION_JPEG);
        $image->setImageCompression(Imagick::COMPRESSION_JPEG);
        $image->setCompressionQuality(75);
        $image->setImageCompressionQuality(75);

        //$this->annotate($width, $height, $image);

        $image->writeImage($newFilePath);
        $image->clear();
    }

    protected function getFormat(): string
    {
        return 'jpeg';
    }
}
