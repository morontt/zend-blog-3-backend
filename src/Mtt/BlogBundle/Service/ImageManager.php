<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 03.04.16
 * Time: 23:17
 */

namespace Mtt\BlogBundle\Service;

use Doctrine\ORM\EntityManager;
use Mtt\BlogBundle\Entity\MediaFile;
use Symfony\Component\Filesystem\Filesystem;
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
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $description
     * @param string $postId
     * @param UploadedFile $file
     */
    public function uploadImage($description, $postId, UploadedFile $file)
    {
        $fileName = $file->getClientOriginalName();
        $file->move($this->getTempDirectory(), $fileName);

        $localPath = $this->getTempDirectory() . '/' . $fileName;
        $this->preprocessing($localPath);

        $remotePath = $this->getPrefixPath() . $fileName;
        $size = filesize($localPath);

        $fs = new Filesystem();
        $fs->copy(
            $localPath,
            static::getUploadsDir() . '/' . $remotePath,
            true
        );

        $media = $this->getMediaFile($remotePath);
        $media
            ->setDescription($description ?: null)
            ->setFileSize($size)
        ;

        if ($postId) {
            $post = $this->em->getRepository('MttBlogBundle:Post')->find((int)$postId);
            if ($post) {
                $media->setPost($post);
                if ($this->em->getRepository('MttBlogBundle:MediaFile')->getCountByPostId($postId) == 0) {
                    $media->setDefaultImage(true);
                }
            }
        }

        $this->em->persist($media);
        $this->em->flush();

        unlink($localPath);
    }

    /**
     * @param MediaFile $entity
     */
    public function remove(MediaFile $entity)
    {
        $fs = new Filesystem();
        $fs->remove(static::getUploadsDir() . '/' . $entity->getPath());

        $this->em->remove($entity);
        $this->em->flush();
    }

    /**
     * @return string
     */
    public static function getUploadsDir(): string
    {
        return '/var/www/resources' . static::getImageBasePath();
    }

    /**
     * @return string
     */
    public static function getImageBasePath(): string
    {
        return '/uploads';
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
     *
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
