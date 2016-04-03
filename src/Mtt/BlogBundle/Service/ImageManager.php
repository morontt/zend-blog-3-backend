<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 03.04.16
 * Time: 23:17
 */

namespace Mtt\BlogBundle\Service;

use Aws\S3\S3Client;
use Doctrine\ORM\EntityManager;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
use Mtt\BlogBundle\Entity\MediaFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ImageManager
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var Filesystem
     */
    protected $remoteFs;

    /**
     * @param array $options
     * @param EntityManager $em
     */
    public function __construct(array $options, EntityManager $em)
    {
        $this->em = $em;

        $client = new S3Client([
            'region' => $options['region'],
            'version' => 'latest',
            'credentials' => [
                'key'    => $options['key'],
                'secret' => $options['secret'],
            ],
        ]);

        $this->remoteFs = new Filesystem(new AwsS3Adapter($client, $options['bucket']));
    }

    /**
     * @param string $description
     * @param string $postId
     * @param UploadedFile $file
     * @return bool
     */
    public function uploadImage($description, $postId, UploadedFile $file)
    {
        $fileName = $file->getClientOriginalName();
        $file->move($this->getTempDirectory(), $fileName);

        $localPath = $this->getTempDirectory() . '/' . $fileName;
        $this->preprocessing($localPath);

        $remotePath = $this->getPrefixPath() . $fileName;
        $size = filesize($localPath);

        $f = fopen($localPath, 'rb');
        $binary = fread($f, $size);
        fclose($f);

        $put = $this->remoteFs->put($remotePath, $binary);
        if ($put) {
            $media = $this->getMediaFile($remotePath);
            $media
                ->setDescription($description ?: null)
                ->setFileSize($size)
            ;

            if ($postId) {
                $post = $this->em->getRepository('MttBlogBundle:Post')->find((int)$postId);
                if ($post) {
                    $media->setPost($post);
                }
            }

            $this->em->persist($media);
            $this->em->flush();
        }

        unlink($localPath);

        return $put;
    }

    /**
     * @param MediaFile $entity
     */
    public function remove(MediaFile $entity)
    {
        if ($this->remoteFs->delete($entity->getPath())) {
            $this->em->remove($entity);
            $this->em->flush();
        }
    }

    /**
     * @param string $path
     */
    protected function preprocessing($path)
    {
        if (strtolower(pathinfo($path, PATHINFO_EXTENSION)) == 'png') {
            $process = new Process('/usr/bin/pngquant -s1 --quality=60-80 --ext .png -f ' . escapeshellarg($path));
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
        }
    }

    /**
     * @param string $remotePath
     * @return MediaFile
     */
    protected function getMediaFile($remotePath)
    {
        $media = $this->em->getRepository('MttBlogBundle:MediaFile')->findOneBy(['path' => $remotePath]);
        if ($media) {
            $media->setLastUpdate(new \DateTime());
        } else {
            $media = new MediaFile();
            $media->setPath($remotePath);
        }

        return $media;
    }

    /**
     * @return mixed
     */
    protected function getPrefixPath()
    {
        return sprintf('blog/%s/', (new \DateTime())->format('Y/m'));
    }

    /**
     * @return mixed
     */
    protected function getTempDirectory()
    {
        return realpath(__DIR__ . '/../../../../var/tmp');
    }
}
