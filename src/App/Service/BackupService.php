<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 07.10.17
 * Time: 16:33
 */

namespace App\Service;

use App\Entity\SystemParameters;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;

class BackupService
{
    public const DUMPS_PATH = '/db_dumps';
    public const DUMPS_COUNT = 14;
    public const IMAGES_PATH = '/blog_images';

    private static ?Dropbox $client = null;

    /**
     * @var string
     */
    private string $dropboxKey;

    /**
     * @var string
     */
    private string $dropboxSecret;

    /**
     * @var SystemParametersStorage
     */
    private SystemParametersStorage $storage;

    /**
     * @param string $dropboxKey
     * @param string $dropboxSecret
     * @param SystemParametersStorage $storage
     */
    public function __construct(string $dropboxKey, string $dropboxSecret, SystemParametersStorage $storage)
    {
        $this->dropboxKey = $dropboxKey;
        $this->dropboxSecret = $dropboxSecret;
        $this->storage = $storage;
    }

    /**
     * @param string $dropboxFile
     * @param string $path
     *
     * @throws \Kunnu\Dropbox\Exceptions\DropboxClientException
     */
    public function upload(string $dropboxFile, string $path)
    {
        $this->getDropboxClient()->uploadChunked($dropboxFile, $path, null, 2097152);
    }

    /**
     * @param string $path
     *
     * @throws \Kunnu\Dropbox\Exceptions\DropboxClientException
     */
    public function delete(string $path): void
    {
        $this->getDropboxClient()->delete($path);
    }

    /**
     * @param string $dir
     *
     * @throws \Kunnu\Dropbox\Exceptions\DropboxClientException
     *
     * @return array
     */
    public function filesByDir(string $dir): array
    {
        $files = [];
        /* @var \Kunnu\Dropbox\Models\FileMetadata $item */
        foreach ($this->getDropboxClient()->listFolder($dir)->getItems() as $item) {
            if ($item->getTag() === 'file') {
                $files[] = $item->getPathDisplay();
            }
        }

        return $files;
    }

    /**
     * @throws \Kunnu\Dropbox\Exceptions\DropboxClientException
     *
     * @return Dropbox
     */
    private function getDropboxClient(): Dropbox
    {
        if (static::$client) {
            return static::$client;
        }

        $app = new DropboxApp(
            $this->dropboxKey,
            $this->dropboxSecret,
            $this->storage->getParameter(SystemParameters::DROPBOX_TOKEN)
        );
        $client = new Dropbox($app);
        static::$client = $client;

        return $client;
    }
}
