<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 10.04.16
 * Time: 17:29
 */

namespace Mtt\BlogBundle\Service;

use Doctrine\ORM\EntityManager;
use Mtt\BlogBundle\Entity\Post;
use Mtt\BlogBundle\Entity\Repository\MediaFileRepository;

class TextProcessor
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var string
     */
    private $imageBasepath;

    /**
     * @var MediaFileRepository
     */
    private $mediaFileRepository;

    /**
     * @param MediaFileRepository $mediaFileRepository
     * @param string $cdn
     */
    public function __construct(MediaFileRepository $mediaFileRepository, string $cdn)
    {
        $this->mediaFileRepository = $mediaFileRepository;
        $this->imageBasepath = $cdn . ImageManager::getImageBasePath() . '/';
    }

    /**
     * @param Post $entity
     */
    public function processing(Post $entity)
    {
        $entity->setText($this->imageProcessing($entity->getRawText()));
        $entity->setPreview($this->preview($entity->getRawText()));
    }

    /**
     * @param string $text
     *
     * @return string
     */
    private function imageProcessing(string $text): string
    {
        return preg_replace_callback(
            '/!(?<id>\d+)(?:\((?<alt>[^\)]+)\))?!/m',
            [$this, 'replaceImagesWithDefault'],
            $text
        );
    }

    /**
     * @param string $text
     *
     * @return string
     */
    private function preview(string $text): string
    {
        $preview = explode('<!-- cut -->', $text);

        return preg_replace_callback(
            '/(<p>)?!(?<id>\d+)(\((?<alt>[^)]+)\))?!(<\/p>)?/m',
            [$this, 'replaceImagesWithoutDefault'],
            $preview[0]
        );
    }

    public function replaceImagesWithDefault(array $matches)
    {
        return $this->replaceImages($matches);
    }

    public function replaceImagesWithoutDefault(array $matches)
    {
        return $this->replaceImages($matches, false);
    }

    private function replaceImages(array $matches, bool $withDefault = true)
    {
        $media = $this->mediaFileRepository->find((int)$matches['id']);
        if ($media) {
            if ($withDefault || !$media->isDefaultImage()) {
                $alt = $matches['alt'] ?? $media->getDescription();
                $replace = sprintf(
                    '<img src="%s" alt="%s" title="%s"/>',
                    $this->imageBasepath . $media->getPath(),
                    $alt,
                    $alt
                );
            } else {
                $replace = '';
            }
        } else {
            $replace = '<b>UNDEFINED</b>';
        }

        return $replace;
    }
}
