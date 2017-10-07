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
use Mtt\BlogBundle\Entity\SystemParameters;

class DropboxService
{
    /**
     * @var Dropbox
     */
    protected $dropbox;

    /**
     * @param string $dropboxKey
     * @param string $dropboxSecret
     * @param SystemParametersStorage $storage
     */
    public function __construct(string $dropboxKey, string $dropboxSecret, SystemParametersStorage $storage)
    {
        $app = new DropboxApp($dropboxKey, $dropboxSecret, $storage->getParameter(SystemParameters::DROPBOX_TOKEN));
        $this->dropbox = new Dropbox($app);
    }

    /**
     * @param string $dropboxFile
     * @param string $path
     *
     * @return \Kunnu\Dropbox\Models\FileMetadata
     */
    public function uploadChunked(string $dropboxFile, string $path)
    {
        return $this->dropbox->uploadChunked($dropboxFile, $path, null, 2097152);
    }

    /**
     * @param string $dropboxFile
     * @param string $path
     *
     * @return \Kunnu\Dropbox\Models\FileMetadata
     */
    public function upload(string $dropboxFile, string $path)
    {
        return $this->dropbox->upload($dropboxFile, $path);
    }
}
