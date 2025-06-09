<?php

/**
 * User: morontt
 * Date: 18.10.2024
 * Time: 21:52
 */

namespace App\Utils\Flysystem;

use League\Flysystem\Config;
use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\PathPrefixer;
use League\Flysystem\UnableToCheckFileExistence;
use League\Flysystem\UnableToCopyFile;
use League\Flysystem\UnableToCreateDirectory;
use League\Flysystem\UnableToDeleteDirectory;
use League\Flysystem\UnableToDeleteFile;
use League\Flysystem\UnableToMoveFile;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToRetrieveMetadata;
use League\Flysystem\UnableToSetVisibility;
use League\Flysystem\UnableToWriteFile;
use RuntimeException;
use Sabre\DAV\Client;
use Sabre\DAV\Xml\Property\ResourceType;
use Sabre\HTTP\ClientHttpException;
use Sabre\HTTP\Request;
use Throwable;

class WebDAVAdapter implements FilesystemAdapter
{
    public const FIND_PROPERTIES = [
        '{DAV:}displayname',
        '{DAV:}getcontentlength',
        '{DAV:}getcontenttype',
        '{DAV:}getlastmodified',
        '{DAV:}iscollection',
        '{DAV:}resourcetype',
    ];

    private PathPrefixer $prefixer;

    private Client $client;

    public function __construct(
        Client $client,
        string $prefix = '',
    ) {
        $this->client = $client;
        $this->prefixer = new PathPrefixer($prefix);
    }

    public function fileExists(string $path): bool
    {
        $location = $this->encodePath($this->prefixer->prefixPath($path));

        try {
            $properties = $this->client->propFind($location, ['{DAV:}resourcetype', '{DAV:}iscollection']);

            return !$this->propsIsDirectory($properties);
        } catch (Throwable $exception) {
            if ($exception instanceof ClientHttpException && $exception->getHttpStatus() === 404) {
                return false;
            }

            throw UnableToCheckFileExistence::forLocation($path, $exception);
        }
    }

    protected function encodePath(string $path): string
    {
        $parts = explode('/', $path);

        foreach ($parts as $i => $part) {
            $parts[$i] = rawurlencode($part);
        }

        return implode('/', $parts);
    }

    public function directoryExists(string $path): bool
    {
        $location = $this->encodePath($this->prefixer->prefixPath($path));

        try {
            $properties = $this->client->propFind($location, ['{DAV:}resourcetype', '{DAV:}iscollection']);

            return $this->propsIsDirectory($properties);
        } catch (Throwable $exception) {
            if ($exception instanceof ClientHttpException && $exception->getHttpStatus() === 404) {
                return false;
            }

            throw UnableToCheckFileExistence::forLocation($path, $exception);
        }
    }

    public function write(string $path, string $contents, Config $config): void
    {
        $this->upload($path, $contents);
    }

    public function writeStream(string $path, $contents, Config $config): void
    {
        $this->upload($path, $contents);
    }

    /**
     * @param resource|string $contents
     */
    private function upload(string $path, $contents): void
    {
        $this->createParentDirFor($path);
        $location = $this->encodePath($this->prefixer->prefixPath($path));

        try {
            $response = $this->client->request('PUT', $location, $contents);
            $statusCode = $response['statusCode'];

            if ($statusCode < 200 || $statusCode >= 300) {
                throw new RuntimeException('Unexpected status code received: ' . $statusCode);
            }
        } catch (Throwable $exception) {
            throw UnableToWriteFile::atLocation($path, $exception->getMessage(), $exception);
        }
    }

    public function read(string $path): string
    {
        $location = $this->encodePath($this->prefixer->prefixPath($path));

        try {
            $response = $this->client->request('GET', $location);

            if ($response['statusCode'] !== 200) {
                throw new RuntimeException('Unexpected response code for GET: ' . $response['statusCode']);
            }

            return $response['body'];
        } catch (Throwable $exception) {
            throw UnableToReadFile::fromLocation($path, $exception->getMessage(), $exception);
        }
    }

    public function readStream(string $path)
    {
        $location = $this->encodePath($this->prefixer->prefixPath($path));

        try {
            $url = $this->client->getAbsoluteUrl($location);
            $request = new Request('GET', $url);
            $response = $this->client->send($request);
            $status = $response->getStatus();

            if ($status !== 200) {
                throw new RuntimeException('Unexpected response code for GET: ' . $status);
            }

            return $response->getBodyAsStream();
        } catch (Throwable $exception) {
            throw UnableToReadFile::fromLocation($path, $exception->getMessage(), $exception);
        }
    }

    public function delete(string $path): void
    {
        $location = $this->encodePath($this->prefixer->prefixPath($path));

        try {
            $response = $this->client->request('DELETE', $location);
            $statusCode = $response['statusCode'];

            if ($statusCode !== 404 && ($statusCode < 200 || $statusCode >= 300)) {
                throw new RuntimeException('Unexpected status code received while deleting file: ' . $statusCode);
            }
        } catch (Throwable $exception) {
            if (!($exception instanceof ClientHttpException && $exception->getCode() === 404)) {
                throw UnableToDeleteFile::atLocation($path, $exception->getMessage(), $exception);
            }
        }
    }

    public function deleteDirectory(string $path): void
    {
        $location = $this->encodePath($this->prefixer->prefixDirectoryPath($path));

        try {
            $statusCode = $this->client->request('DELETE', $location)['statusCode'];

            if ($statusCode !== 404 && ($statusCode < 200 || $statusCode >= 300)) {
                throw new RuntimeException('Unexpected status code received while deleting file: ' . $statusCode);
            }
        } catch (Throwable $exception) {
            if (!($exception instanceof ClientHttpException && $exception->getCode() === 404)) {
                throw UnableToDeleteDirectory::atLocation($path, $exception->getMessage(), $exception);
            }
        }
    }

    public function createDirectory(string $path, Config $config): void
    {
        $parts = explode('/', $this->prefixer->prefixDirectoryPath($path));
        $directoryParts = [];

        foreach ($parts as $directory) {
            if ($directory === '.' || $directory === '') {
                return;
            }

            $directoryParts[] = $directory;
            $directoryPath = implode('/', $directoryParts) . '/';
            $location = $this->encodePath($directoryPath);

            if ($this->directoryExists($this->prefixer->stripDirectoryPrefix($directoryPath))) {
                continue;
            }

            try {
                $response = $this->client->request('MKCOL', $location);
            } catch (Throwable $exception) {
                throw UnableToCreateDirectory::dueToFailure($path, $exception);
            }

            if ($response['statusCode'] !== 201) {
                throw UnableToCreateDirectory::atLocation($path, 'Failed to create directory at: ' . $location);
            }
        }
    }

    public function setVisibility(string $path, string $visibility): void
    {
        throw UnableToSetVisibility::atLocation($path, 'WebDAV does not support this operation.');
    }

    public function visibility(string $path): FileAttributes
    {
        throw UnableToRetrieveMetadata::visibility($path, 'WebDAV does not support this operation.');
    }

    public function mimeType(string $path): FileAttributes
    {
        $mimeType = (string)$this->propFind($path, 'mime_type', '{DAV:}getcontenttype');

        return new FileAttributes($path, null, null, null, $mimeType);
    }

    public function lastModified(string $path): FileAttributes
    {
        $lastModified = $this->propFind($path, 'last_modified', '{DAV:}getlastmodified');

        return new FileAttributes($path, null, null, strtotime($lastModified));
    }

    public function fileSize(string $path): FileAttributes
    {
        $fileSize = (int)$this->propFind($path, 'file_size', '{DAV:}getcontentlength');

        return new FileAttributes($path, $fileSize);
    }

    public function listContents(string $path, bool $deep): iterable
    {
        $location = $this->encodePath($this->prefixer->prefixDirectoryPath($path));
        $response = $this->client->propFind($location, self::FIND_PROPERTIES, 1);

        // This is the directory itself, the files are subsequent entries.
        array_shift($response);

        foreach ($response as $pathKey => $object) {
            $pathKey = (string)parse_url(rawurldecode($pathKey), PHP_URL_PATH);
            $pathKey = $this->prefixer->stripPrefix($pathKey);
            $object = $this->normalizeObject($object);

            if ($this->propsIsDirectory($object)) {
                yield new DirectoryAttributes($pathKey, null, $object['last_modified'] ?? null);

                if (!$deep) {
                    continue;
                }

                foreach ($this->listContents($pathKey, true) as $child) {
                    yield $child;
                }
            } else {
                yield new FileAttributes(
                    $pathKey,
                    $object['file_size'] ?? null,
                    null,
                    $object['last_modified'] ?? null,
                    $object['mime_type'] ?? null,
                );
            }
        }
    }

    private function normalizeObject(array $object): array
    {
        $mapping = [
            '{DAV:}getcontentlength' => 'file_size',
            '{DAV:}getcontenttype' => 'mime_type',
            'content-length' => 'file_size',
            'content-type' => 'mime_type',
        ];

        foreach ($mapping as $from => $to) {
            if (array_key_exists($from, $object)) {
                $object[$to] = $object[$from];
            }
        }

        array_key_exists('file_size', $object) && $object['file_size'] = (int)$object['file_size'];

        if (array_key_exists('{DAV:}getlastmodified', $object)) {
            $object['last_modified'] = strtotime($object['{DAV:}getlastmodified']);
        }

        return $object;
    }

    public function move(string $source, string $destination, Config $config): void
    {
        if ($source === $destination) {
            return;
        }

        $this->createParentDirFor($destination);
        $location = $this->encodePath($this->prefixer->prefixPath($source));
        $newLocation = $this->encodePath($this->prefixer->prefixPath($destination));

        try {
            $response = $this->client->request('MOVE', $location, null, [
                'Destination' => $this->client->getAbsoluteUrl($newLocation),
            ]);

            if ($response['statusCode'] < 200 || $response['statusCode'] >= 300) {
                throw new RuntimeException('MOVE command returned unexpected status code: ' . $response['statusCode'] . "\n{$response['body']}");
            }
        } catch (Throwable $e) {
            throw UnableToMoveFile::fromLocationTo($source, $destination, $e);
        }
    }

    public function copy(string $source, string $destination, Config $config): void
    {
        if ($source === $destination) {
            return;
        }

        $this->createParentDirFor($destination);
        $location = $this->encodePath($this->prefixer->prefixPath($source));
        $newLocation = $this->encodePath($this->prefixer->prefixPath($destination));

        try {
            $response = $this->client->request('COPY', $location, null, [
                'Destination' => $this->client->getAbsoluteUrl($newLocation),
            ]);

            if ($response['statusCode'] < 200 || $response['statusCode'] >= 300) {
                throw new RuntimeException('COPY command returned unexpected status code: ' . $response['statusCode']);
            }
        } catch (Throwable $e) {
            throw UnableToCopyFile::fromLocationTo($source, $destination, $e);
        }
    }

    private function propsIsDirectory(array $properties): bool
    {
        if (isset($properties['{DAV:}resourcetype'])) {
            /** @var ResourceType $resourceType */
            $resourceType = $properties['{DAV:}resourcetype'];

            return $resourceType->is('{DAV:}collection');
        }

        return isset($properties['{DAV:}iscollection']) && $properties['{DAV:}iscollection'] === '1';
    }

    private function createParentDirFor(string $path): void
    {
        $dirname = dirname($path);

        if ($this->directoryExists($dirname)) {
            return;
        }

        $this->createDirectory($dirname, new Config());
    }

    private function propFind(string $path, string $section, string $property)
    {
        $location = $this->encodePath($this->prefixer->prefixPath($path));

        try {
            $result = $this->client->propFind($location, [$property]);

            if (!array_key_exists($property, $result)) {
                throw new RuntimeException('Invalid response, missing key: ' . $property);
            }

            return $result[$property];
        } catch (Throwable $exception) {
            throw UnableToRetrieveMetadata::create($path, $section, $exception->getMessage(), $exception);
        }
    }
}
