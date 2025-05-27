<?php

namespace App\Model\Resizer;

use Imagick;
use ImagickException;

class JpegResizer extends CommonResizer
{
    use DebugAnnotation;

    /**
     * @throws ImagickException
     */
    public function resize(string $filePath, string $newFilePath, int $width, int $height)
    {
        $image = new Imagick($filePath);
        $this->orientate($image);
        $image->stripImage();

        $image->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 1);

        $image->setFormat($this->getFormat());
        $image->setImageFormat($this->getFormat());

        $image->setCompression(Imagick::COMPRESSION_JPEG);
        $image->setImageCompression(Imagick::COMPRESSION_JPEG);
        $image->setCompressionQuality(75);
        $image->setImageCompressionQuality(75);

        // $this->annotate($width, $height, $image);

        $image->writeImage($newFilePath);
        $image->clear();
    }

    protected function getFormat(): string
    {
        return 'jpeg';
    }
}
