<?php

namespace Mtt\BlogBundle\Model\Resizer;

use Imagick;
use ImagickDraw;
use ImagickPixel;

trait DebugAnnotation
{
    private function annotate($width, $height, Imagick $image)
    {
        /*
        $fontList = Imagick::queryFonts('*');
        foreach ($fontList as $fontName) {
            dump($fontName);
        }
        return */

        $draw = new ImagickDraw();

        $draw->setFont('DejaVu-Sans-Mono');
        $draw->setFontSize(16);
        $draw->setFillColor(new ImagickPixel('#FFFFFF'));
        $draw->setStrokeAntialias(true);
        $draw->setTextAntialias(true);

        $text = sprintf(
            '%s%s %s',
            $width ? $width . 'w' : '',
            $height ? $height . 'h' : '',
            $this->getFormat()
        );

        $metrics = $image->queryFontMetrics($draw, $text);
        $draw->annotation(10, 10 + $metrics['ascender'], $text);

        $image->drawImage($draw);
    }
}
