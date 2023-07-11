<?php

namespace Mtt\BlogBundle\Model;

interface ResizerInterface
{
    public function resize(string $filePath, string $newFilePath, int $width, int $height);
}
