<?php

declare(strict_types=1);

/**
 * User: morontt
 * Date: 19.10.2024
 * Time: 21:57
 */

namespace App\Service\BackUp;

use App\Entity\SystemParameters;
use App\Service\SystemParametersStorage;
use App\Utils\Flysystem\WebDAVAdapter;
use League\Flysystem\Filesystem;
use LogicException;
use Sabre\DAV\Client as SabreClient;
use Spatie\Dropbox\Client as DropboxClient;
use Spatie\FlysystemDropbox\DropboxAdapter;

class FlysystemFactory
{
    public function __construct(
        private SystemParametersStorage $storage,
        private string $spaceLogin,
        private string $spacePassword,
        private string $adapterName,
    ) {
        $this->spaceLogin = $spaceLogin;
        $this->spacePassword = $spacePassword;

        $this->storage = $storage;
    }

    public function createFlysystem(): Filesystem
    {
        $options = [];
        if ($this->adapterName === 'mail_space') {
            $client = new SabreClient([
                'baseUri' => 'https://webdav.cloud.mail.ru',
                'userName' => $this->spaceLogin,
                'password' => $this->spacePassword,
            ]);

            $adapter = new WebDAVAdapter($client, 'zendblog_bucket');
        } elseif ($this->adapterName === 'dropbox') {
            $token = $this->storage->getParameter(SystemParameters::DROPBOX_TOKEN);
            if (!$token) {
                throw new LogicException('Empty dropbox token');
            }
            $client = new DropboxClient(
                $token,
                null,
                2097152
            );

            $adapter = new DropboxAdapter($client);
            $options['case_sensitive'] = false;
        } else {
            throw new LogicException('invalid flysystem name: ' . $this->adapterName);
        }

        return new Filesystem($adapter, $options);
    }
}
