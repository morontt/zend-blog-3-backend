<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 03.04.16
 * Time: 23:17
 */

namespace Mtt\BlogBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Mtt\BlogBundle\Entity\MediaFile;
use Mtt\BlogBundle\Model\Image;
use SimpleXMLElement;
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

    /**
     * @param EntityManagerInterface $em
     * @param string $cdn
     */
    public function __construct(EntityManagerInterface $em, string $cdn)
    {
        $this->em = $em;
        $this->imageBasepath = $cdn . self::getImageBasePath() . '/';
    }

    /**
     * @param $description
     * @param $postId
     * @param UploadedFile $file
     *
     * @throws ORMException
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
     * @param MediaFile $entity
     *
     * @throws ORMException
     */
    public function remove(MediaFile $entity)
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

            if (preg_match('/(?:^\d+_\d+_)?' . $pathInfo['filename'] . '(?:_\d+(?:w|h))?\./', $file)
                && $file !== $baseName
            ) {
                $fs->remove($directory . '/' . $file);
            }
        }
    }

    public function featuredPictureTag(MediaFile $entity): string
    {
        $sizes = [
            '(min-width: 48em) calc(40vw - 2.25rem)',
            'calc(100vw - 1.875rem)',
        ];

        return $this->pictureTag($entity, $sizes, $entity->getDescription());
    }

    public function articlePictureTag(MediaFile $entity, ?string $alt): string
    {
        $sizes = [
            '(min-width: 64em) calc(100vw - 280px - 11.25rem)', // sidebar 280px and paddings 7.5rem + 3.75rem
            '(min-width: 48em) calc(100vw - 9.375rem)',
            'calc(100vw - 1.875rem)',
        ];

        return $this->pictureTag($entity, $sizes, $alt);
    }

    public function previewPictureTag(MediaFile $entity, ?string $alt): string
    {
        $sizes = [
            '(min-width: 48em) calc(60vw - 9.625rem)',
            '(min-width: 40.063em) calc(100vw - 10rem)',
            'calc(100vw - 5.625rem)',
        ];

        return $this->pictureTag($entity, $sizes, $alt);
    }

    public function pictureTag(MediaFile $entity, array $sizes, ?string $alt): string
    {
        $image = new Image($entity);
        $xml = new SimpleXMLElement('<picture/>');

        $srcSet = $image->getSrcSet();

        if ($avifSet = $srcSet->getAvif()) {
            $sourceAvif = $xml->addChild('source');

            $srcSetStrings = array_map(
                function (array $el) {
                    return $this->imageBasepath . $el['path'] . ' ' . $el['width'] . 'w';
                },
                $avifSet->getItems()
            );
            $sourceAvif->addAttribute('srcset', implode(', ', $srcSetStrings));
            $sourceAvif->addAttribute('sizes', implode(', ', $sizes));
            $sourceAvif->addAttribute('type', $avifSet->getMIMEType());
        }

        if ($webpSet = $srcSet->getWebp()) {
            $sourceWebp = $xml->addChild('source');

            $srcSetStrings = array_map(
                function (array $el) {
                    return $this->imageBasepath . $el['path'] . ' ' . $el['width'] . 'w';
                },
                $webpSet->getItems()
            );
            $sourceWebp->addAttribute('srcset', implode(', ', $srcSetStrings));
            $sourceWebp->addAttribute('sizes', implode(', ', $sizes));
            $sourceWebp->addAttribute('type', $webpSet->getMIMEType());
        }

        $img = $xml->addChild('img');

        $srcSetStrings = array_map(
            function (array $el) {
                return $this->imageBasepath . $el['path'] . ' ' . $el['width'] . 'w';
            },
            $srcSet->getOrigin()->getItems()
        );

        $files = $srcSet->getOrigin()->getItems();
        $first = reset($files);

        $img->addAttribute('src', $this->imageBasepath . $first['path']);
        $img->addAttribute('width', $first['width']);
        $img->addAttribute('height', $first['height']);
        if ($alt) {
            $img->addAttribute('alt', $alt);
            $img->addAttribute('title', $alt);
        }

        $img->addAttribute('srcset', implode(', ', $srcSetStrings));
        $img->addAttribute('sizes', implode(', ', $sizes));

        return str_replace("<?xml version=\"1.0\"?>\n", '', $xml->asXML());
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
        return APP_VAR_DIR . '/tmp';
    }
}
