<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 07.10.17
 * Time: 16:33
 */

namespace App\Service;

use App\Entity\SystemParameters;
use League\Flysystem\Filesystem;
use League\Flysystem\StorageAttributes;
use Spatie\Dropbox\Client;
use Spatie\FlysystemDropbox\DropboxAdapter;

class BackupService
{
    public const DUMPS_PATH = '/db_dumps';
    public const DUMPS_COUNT = 14;
    public const IMAGES_PATH = '/blog_images';

    private static ?Filesystem $flySystem = null;

    /**
     * @var SystemParametersStorage
     */
    private SystemParametersStorage $storage;

    /**
     * @param SystemParametersStorage $storage
     */
    public function __construct(SystemParametersStorage $storage)
    {
        $this->storage = $storage;
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
        /* @var StorageAttributes $item */
        foreach ($listing as $item) {
            $files[] = $item->path();
        }

        return $files;
    }

    /**
     * @return Filesystem
     */
    private function getFlySystem(): Filesystem
    {
        if (static::$flySystem) {
            return static::$flySystem;
        }

        $client = new Client(
            $this->storage->getParameter(SystemParameters::DROPBOX_TOKEN),
            null,
            2097152
        );

        $flySystem = new Filesystem(new DropboxAdapter($client), ['case_sensitive' => false]);
        static::$flySystem = $flySystem;

        return $flySystem;
    }
}
