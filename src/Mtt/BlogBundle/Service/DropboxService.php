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
    public function uploadChunked(string $dropboxFile, string $path)
    {
        return $this->getDropboxClient()->uploadChunked($dropboxFile, $path, null, 2097152);
    }

    /**
     * @param string $dropboxFile
     * @param string $path
     *
     * @return FileMetadata
     */
    public function upload(string $dropboxFile, string $path)
    {
        return $this->getDropboxClient()->upload($dropboxFile, $path);
    }

    /**
     * @return Dropbox
     */
    protected function getDropboxClient(): Dropbox
    {
        $app = new DropboxApp(
            $this->dropboxKey,
            $this->dropboxSecret,
            $this->storage->getParameter(SystemParameters::DROPBOX_TOKEN)
        );

        return new Dropbox($app);
    }
}
