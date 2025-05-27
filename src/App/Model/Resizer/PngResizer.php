<?php

namespace App\Model\Resizer;

use Imagick;
use ImagickException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class PngResizer extends CommonResizer
{
    use DebugAnnotation;

    /**
     * @throws ImagickException
     * @throws ProcessFailedException
     */
    public function resize(string $filePath, string $newFilePath, int $width, int $height)
    {
        $image = new Imagick($filePath);
        $this->orientate($image);
        $image->stripImage();

        $image->scaleImage($width, $height);

        $image->setFormat($this->getFormat());
        $image->setImageFormat($this->getFormat());

        $image->setCompression(Imagick::COMPRESSION_ZIP);
        $image->setImageCompression(Imagick::COMPRESSION_ZIP);

        // $this->annotate($width, $height, $image);

        $image->writeImage($newFilePath);
        $image->clear();

        $process = new Process([
            '/usr/bin/pngquant',
            '-s1',
            '--quality=60-80',
            '--ext',
            '.png',
            '-f',
            $newFilePath,
        ]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }

    protected function getFormat(): string
    {
        return 'png';
    }
}
