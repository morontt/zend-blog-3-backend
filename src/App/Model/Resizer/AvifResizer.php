<?php

namespace App\Model\Resizer;

use App\Model\ResizerInterface;
use Imagick;
use ImagickException;
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

        /**
         * TODO rework:
         *
         * User Deprecated: Passing a command as string when creating a "Symfony\Component\Process\Process" instance
         * is deprecated since Symfony 4.2, pass it as an array of its arguments instead, or use
         * the "Process::fromShellCommandline()" constructor if you need features provided by the shell.
         */
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
        if (file_exists($resourcePath . '/' . $newFilePath)) {
            return $newFilePath;
        }

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
