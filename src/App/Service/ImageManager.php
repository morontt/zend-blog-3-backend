<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 03.04.16
 * Time: 23:17
 */

namespace App\Service;

use App\Entity\Commentator;
use App\Entity\MediaFile;
use App\Entity\Post;
use App\Event\UpdateCommentatorEvent;
use App\Exception\ObjectNotFoundException;
use App\Model\Image;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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

    private string $imageBasepath;

    private EventDispatcherInterface $dispatcher;

    public function __construct(
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        string $cdnUrl,
    ) {
        $this->em = $em;
        $this->imageBasepath = $cdnUrl . self::getImageBasePath() . '/';
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param $description
     * @param $postId
     * @param UploadedFile $file
     *
     * @throws \Doctrine\ORM\Exception\NotSupported
     * @throws \Doctrine\ORM\Exception\ORMException
     */
    public function uploadImage($description, $postId, UploadedFile $file): void
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
            $post = $this->em->getRepository(Post::class)->find((int)$postId);
            if ($post) {
                $media->setPost($post);
                if ($this->em->getRepository(MediaFile::class)->getCountByPostId($postId) === 0) {
                    $media->setDefaultImage(true);
                }
            }
        }

        if ($media->isImage()) {
            $image = new Image($media);
            $geometry = $image->getImageGeometry();
            $media
                ->setWidth($geometry->width)
                ->setHeight($geometry->height)
            ;
        }

        $this->em->persist($media);
        $this->em->flush();

        unlink($localPath);
    }

    /**
     * @throws ObjectNotFoundException
     */
    public function uploadAvatar($commentatorId, UploadedFile $file): void
    {
        $commentator = $this->em->getRepository(Commentator::class)->find((int)$commentatorId);
        if (!$commentator) {
            throw new ObjectNotFoundException();
        }

        switch ($file->getMimeType()) {
            case 'image/png':
                $ext = '.png';
                break;
            case 'image/jpeg':
                $ext = '.jpg';
                break;
            default:
                $ext = '.out';
        }

        $fileName = $file->getClientOriginalName();
        $file->move($this->getTempDirectory(), $fileName);

        $localPath = $this->getTempDirectory() . '/' . $fileName;

        // TODO check file is exist
        $remotePath = '/var/www/resources/data/pictures/commentator.' . $commentator->getId() . $ext;

        $fs = new Filesystem();
        $fs->copy(
            $localPath,
            $remotePath,
            true
        );

        unlink($localPath);

        $commentator->setAvatarVariant(1 + $commentator->getAvatarVariant());
        $this->em->flush();

        $this->dispatcher->dispatch(new UpdateCommentatorEvent($commentator));
    }

    /**
     * @param MediaFile $entity
     *
     * @throws \Doctrine\ORM\Exception\ORMException
     *
     * @return void
     */
    public function remove(MediaFile $entity): void
    {
        $this->removeAllPreview($entity);

        $fs = new Filesystem();
        $fs->remove(static::getUploadsDir() . '/' . $entity->getPath());

        $this->em->remove($entity);
        $this->em->flush();
    }

    public function removeAllPreview(MediaFile $entity)
    {
        $pathInfo = pathinfo(static::getUploadsDir() . '/' . $entity->getPath());
        $directory = $pathInfo['dirname'];
        $baseName = $pathInfo['basename'];

        $fs = new Filesystem();
        foreach (scandir($directory) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            if ($file !== $baseName
                && preg_match('/(?:^\d+_\d+_)?' . $pathInfo['filename'] . '(?:_\d+(?:w|h))?\./', $file)
            ) {
                $fs->remove($directory . '/' . $file);
            }
        }
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

    public function cdnImagePath(): string
    {
        return $this->imageBasepath;
    }

    /**
     * @param string $path
     */
    protected function preprocessing($path)
    {
        if (strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'png') {
            $process = new Process(['/usr/bin/pngquant', '-s1', '--quality=60-80', '--ext', '.png', '-f', $path]);
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
        $media = $this->em->getRepository(MediaFile::class)->findOneBy(['path' => $remotePath]);
        if ($media) {
            $media->setLastUpdate(new DateTime());
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
        return sprintf('blog/%s/', (new DateTime())->format('Y/m'));
    }

    /**
     * @return mixed
     */
    protected function getTempDirectory()
    {
        return APP_VAR_DIR . '/tmp';
    }
}
