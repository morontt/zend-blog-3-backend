<?php

/**
 * User: morontt
 * Date: 19.10.2024
 * Time: 21:57
 */

namespace App\Service\BackUp;

use App\Entity\SystemParameters;
use App\Service\SystemParametersStorage;
use App\Utils\Flysystem\WebDAVAdapter;
use InvalidArgumentException;
use League\Flysystem\Filesystem;
use Sabre\DAV\Client as SabreClient;
use Spatie\Dropbox\Client as DropboxClient;
use Spatie\FlysystemDropbox\DropboxAdapter;

class FlysystemFactory
{
    private string $spaceLogin;
    private string $spacePassword;

    private SystemParametersStorage $storage;

    /**
     * @param SystemParametersStorage $storage
     * @param string $spaceLogin
     * @param string $spacePassword
     */
    public function __construct(SystemParametersStorage $storage, string $spaceLogin, string $spacePassword)
    {
        $this->spaceLogin = $spaceLogin;
        $this->spacePassword = $spacePassword;

        $this->storage = $storage;
    }

    public function createFlysystem(string $name): Filesystem
    {
        $options = [];
        if ($name === 'mail_space') {
            $client = new SabreClient([
                'baseUri' => 'https://webdav.cloud.mail.ru',
                'userName' => $this->spaceLogin,
                'password' => $this->spacePassword,
            ]);

            $adapter = new WebDAVAdapter($client, 'zendblog_bucket');
        } elseif ($name === 'dropbox') {
            $client = new DropboxClient(
                $this->storage->getParameter(SystemParameters::DROPBOX_TOKEN),
                null,
                2097152
            );

            $adapter = new DropboxAdapter($client);
            $options['case_sensitive'] = false;
        } else {
            throw new InvalidArgumentException('invalid flysystem name: ' . $name);
        }

        return new Filesystem($adapter, $options);
    }
}
