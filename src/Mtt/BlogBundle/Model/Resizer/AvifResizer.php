<?php

namespace Mtt\BlogBundle\Model\Resizer;

use Imagick;
use ImagickException;
use Mtt\BlogBundle\Model\ResizerInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class AvifResizer implements ResizerInterface
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

        $image->setFormat('png');
        $image->setImageFormat('png');

        $image->setCompression(Imagick::COMPRESSION_ZIP);
        $image->setImageCompression(Imagick::COMPRESSION_ZIP);

        //$this->annotate($width, $height, $image);

        $tmpfile = sys_get_temp_dir() . '/' . uniqid() . '.png';
        $image->writeImage($tmpfile);
        $image->clear();

        $process = new Process(
            '/usr/bin/cavif --quality=75 --speed=1 --depth=8 --quiet -o ' . escapeshellarg($newFilePath) . ' ' . $tmpfile
        );
        $process->run();
        unlink($tmpfile);

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }

    /**
     * @throws ImagickException
     */
    public function convert(string $filePath, string $resourcePath): string
    {
        $pathInfo = pathinfo($filePath);
        $newFilePath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.avif';

        $image = new Imagick($resourcePath . '/' . $filePath);
        $image->stripImage();

        $image->setFormat('png');
        $image->setImageFormat('png');

        $image->setCompression(Imagick::COMPRESSION_ZIP);
        $image->setImageCompression(Imagick::COMPRESSION_ZIP);

        //$this->annotate(0, 0, $image);

        $tmpfile = sys_get_temp_dir() . '/' . uniqid() . '.png';
        $image->writeImage($tmpfile);
        $image->clear();

        $process = new Process(
            '/usr/bin/cavif --quality=75 --speed=1 --depth=8 --quiet -o ' . escapeshellarg($resourcePath . '/' . $newFilePath)
            . ' ' . $tmpfile
        );
        $process->run();
        unlink($tmpfile);

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $newFilePath;
    }

    protected function getFormat(): string
    {
        return 'avif';
    }
}
