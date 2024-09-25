<?php

namespace App\Model\Resizer;

use App\Model\ResizerInterface;
use Imagick;
use ImagickException;

class WebpResizer implements ResizerInterface
{
    use DebugAnnotation;

    /**
     * @throws ImagickException
     */
    public function resize(string $filePath, string $newFilePath, int $width, int $height)
    {
        if (!Imagick::queryFormats('WEBP')) {
            throw new \RuntimeException('Webp format is not supported by Imagick installation.');
        }

        $image = new Imagick($filePath);
        $image->stripImage();

        $image->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 1);

        $image->setFormat($this->getFormat());
        $image->setImageFormat($this->getFormat());

        $image->setCompression(Imagick::COMPRESSION_JPEG);
        $image->setImageCompression(Imagick::COMPRESSION_JPEG);
        $image->setImageCompressionQuality(80);

        //$this->annotate($width, $height, $image);

        $image->writeImage($newFilePath);
        $image->clear();
    }

    /**
     * @throws ImagickException
     */
    public function convert(string $filePath, string $resourcePath): string
    {
        if (!Imagick::queryFormats('WEBP')) {
            throw new \RuntimeException('Webp format is not supported by Imagick installation.');
        }

        $pathInfo = pathinfo($filePath);
        $newFilePath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
        if (file_exists($resourcePath . '/' . $newFilePath)) {
            return $newFilePath;
        }

        $image = new Imagick($resourcePath . '/' . $filePath);
        $image->stripImage();

        $image->setFormat($this->getFormat());
        $image->setImageFormat($this->getFormat());

        $image->setCompression(Imagick::COMPRESSION_JPEG);
        $image->setImageCompression(Imagick::COMPRESSION_JPEG);
        $image->setImageCompressionQuality(80);

        //$this->annotate(0, 0, $image);

        $image->writeImage($resourcePath . '/' . $newFilePath);
        $image->clear();

        return $newFilePath;
    }

    protected function getFormat(): string
    {
        return 'webp';
    }
}
