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
use Aws\S3\S3Client;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\Filesystem;
use League\Flysystem\WebDAV\WebDAVAdapter;
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
        private string $s3Region,
        private string $s3Endpoint,
        private string $s3BacketName,
        private string $s3AccessKey,
        private string $s3Secret,
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
        } elseif ($this->adapterName === 's3') {
            $client = new S3Client([
                'region' => $this->s3Region,
                'use_path_style_endpoint' => false,
                'credentials' => [
                    'key' => $this->s3AccessKey,
                    'secret' => $this->s3Secret,
                ],
                'endpoint' => 'https://' . $this->s3Endpoint,
            ]);

            $adapter = new AwsS3V3Adapter(
                $client,
                $this->s3BacketName,
                'reprogl'
            );
        } else {
            throw new LogicException('invalid flysystem name: ' . $this->adapterName);
        }

        return new Filesystem($adapter, $options);
    }
}
