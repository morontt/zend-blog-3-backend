<?php

namespace Mtt\BlogBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Assets extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction(
                'assets',
                [$this, 'assets'],
                [
                    'is_safe' => ['html'],
                ]
            ),
        ];
    }

    public function assets(string $path): string
    {
        $matches = [];
        if (!preg_match('/^(?P<prefix>.+)\.(?P<ext>css|js)$/', $path, $matches)) {
            return "<unknown src=\"{$path}\"/>";
        }

        if ($matches['ext'] === 'css') {
            $format = '<link rel="stylesheet" href="%s.v%d.css">';
        } else {
            $format = '<script src="%s.v%d.js"></script>';
        }

        return sprintf($format, $matches['prefix'], $this->version($path));
    }

    /**
     * @param string $path
     *
     * @return int
     */
    private function version(string $path): int
    {
        $resourceRoot = realpath(__DIR__ . '/../../../../../web');
        $file = $resourceRoot . $path;

        if (!is_file($file)) {
            throw new AssetNotExistException('File not exist: ' . $file);
        }

        $mtime = (int)filemtime($file);

        return $mtime - (($mtime >> 20) << 20);
    }
}
