<?php

namespace Mtt\BlogBundle\Model\Resizer;

use Imagick;
use ImagickException;
use Mtt\BlogBundle\Model\ResizerInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class PngResizer implements ResizerInterface
{
    /**
     * @throws ImagickException
     * @throws ProcessFailedException
     */
    public function resize(string $filePath, string $newFilePath, int $width, int $height)
    {
        $image = new Imagick($filePath);
        $image->stripImage();

        $image->scaleImage($width, $height);

        $format = 'png';
        $image->setFormat($format);
        $image->setImageFormat($format);

        $image->setCompression(Imagick::COMPRESSION_ZIP);
        $image->setImageCompression(Imagick::COMPRESSION_ZIP);

        $image->writeImage($newFilePath);
        $image->clear();

        $process = new Process(
            '/usr/bin/pngquant -s1 --quality=60-80 --ext .png -f ' . escapeshellarg($newFilePath)
        );
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }
}
