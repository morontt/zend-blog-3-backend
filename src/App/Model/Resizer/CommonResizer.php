<?php

/**
 * User: morontt
 * Date: 27.05.2025
 * Time: 23:56
 */

namespace App\Model\Resizer;

use App\Model\ResizerInterface;
use Imagick;

abstract class CommonResizer implements ResizerInterface
{
    abstract public function resize(string $filePath, string $newFilePath, int $width, int $height);

    protected function orientate(Imagick $image): void
    {
        switch ($image->getImageOrientation()) {
            case Imagick::ORIENTATION_TOPRIGHT:
                $image->flopImage();
                break;
            case Imagick::ORIENTATION_BOTTOMRIGHT:
                $image->rotateImage('#000', 180);
                break;
            case Imagick::ORIENTATION_BOTTOMLEFT:
                $image->rotateImage('#000', 180);
                $image->flopImage();
                break;
            case Imagick::ORIENTATION_LEFTTOP:
                $image->rotateImage('#000', -270);
                $image->flopImage();
                break;
            case Imagick::ORIENTATION_RIGHTTOP:
                $image->rotateImage('#000', -270);
                break;
            case Imagick::ORIENTATION_RIGHTBOTTOM:
                $image->rotateImage('#000', -90);
                $image->flopImage();
                break;
            case Imagick::ORIENTATION_LEFTBOTTOM:
                $image->rotateImage('#000', -90);
                break;
        }

        $image->setImageOrientation(Imagick::ORIENTATION_TOPLEFT);
    }
}
