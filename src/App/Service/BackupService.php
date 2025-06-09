<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 07.10.17
 * Time: 16:33
 */

namespace App\Service;

use League\Flysystem\FilesystemOperator;
use League\Flysystem\StorageAttributes;

class BackupService
{
    public const DUMPS_PATH = '/db_dumps';
    public const DUMPS_COUNT = 14;
    public const IMAGES_PATH = '/blog_images';

    private FilesystemOperator $flySystem;

    public function __construct(FilesystemOperator $flySystem)
    {
        $this->flySystem = $flySystem;
    }

    /**
     * @param string $remotePath
     *
     * @throws \League\Flysystem\FilesystemException
     *
     * @return bool
     */
    public function fileExists(string $remotePath): bool
    {
        return $this->flySystem->fileExists($remotePath);
    }

    /**
     * @param string $localPath
     * @param string $remotePath
     *
     * @throws \League\Flysystem\FilesystemException
     *
     * @return void
     */
    public function upload(string $localPath, string $remotePath): void
    {
        $fp = fopen($localPath, 'rb');
        $this->flySystem->writeStream($remotePath, $fp);
    }

    /**
     * @param string $path
     *
     * @throws \League\Flysystem\FilesystemException
     *
     * @return void
     */
    public function delete(string $path): void
    {
        $this->flySystem->delete($path);
    }

    /**
     * @param string $dir
     *
     * @throws \League\Flysystem\FilesystemException
     *
     * @return string[]
     */
    public function filesByDir(string $dir): array
    {
        $files = [];
        $listing = $this
            ->flySystem
            ->listContents($dir)
            ->filter(fn (StorageAttributes $attributes) => $attributes->isFile())
        ;
        /* @var StorageAttributes $item */
        foreach ($listing as $item) {
            $files[] = $item->path();
        }

        return $files;
    }
}
