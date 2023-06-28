<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 07.10.17
 * Time: 16:33
 */

namespace Mtt\BlogBundle\Service;

use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\Models\FileMetadata;
use Mtt\BlogBundle\Entity\SystemParameters;

class DropboxService
{
    private static ?Dropbox $client = null;

    /**
     * @var string
     */
    private $dropboxKey;

    /**
     * @var string
     */
    private $dropboxSecret;

    /**
     * @var SystemParametersStorage
     */
    private $storage;

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
     * @return FileMetadata
     */
    public function upload(string $dropboxFile, string $path)
    {
        return $this->getDropboxClient()->uploadChunked($dropboxFile, $path, null, 2097152);
    }

    /**
     * @param string $path
     */
    public function delete(string $path)
    {
        $this->getDropboxClient()->delete($path);
    }

    /**
     * @param string $dir
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
     * @return Dropbox
     */
    protected function getDropboxClient(): Dropbox
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
