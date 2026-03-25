<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 07.10.17
 * Time: 16:33
 */

namespace App\Service;

use App\Service\BackUp\FlysystemFactory;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\StorageAttributes;

class BackupService
{
    public const DUMPS_PATH = '/db_dumps';
    public const DUMPS_COUNT = 14;
    public const IMAGES_PATH = '/blog_images';

    private ?FilesystemOperator $flySystem = null;

    public function __construct(private FlysystemFactory $flysystemFactory)
    {
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
        return $this->getFlySystem()->fileExists($remotePath);
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
        $this->getFlySystem()->writeStream($remotePath, $fp);
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
        $this->getFlySystem()->delete($path);
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
            ->getFlySystem()
            ->listContents($dir)
            ->filter(fn (StorageAttributes $attributes) => $attributes->isFile())
        ;
        /** @var StorageAttributes $item */
        foreach ($listing as $item) {
            $files[] = $item->path();
        }

        return $files;
    }

    private function getFlySystem(): FilesystemOperator
    {
        if (is_null($this->flySystem)) {
            $this->flySystem = $this->flysystemFactory->createFlysystem();
        }

        return $this->flySystem;
    }
}
