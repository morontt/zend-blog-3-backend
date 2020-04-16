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
    protected $em;

    /**
     * @var string
     */
    protected $imageBasepath;

    /**
     * @var MediaFileRepository
     */
    protected $mediaFileRepository;

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
    }

    /**
     * @param $text
     *
     * @return string
     */
    public function imageProcessing($text)
    {
        $func = function (array $matches) {
            $media = $this->mediaFileRepository->find((int)$matches[1]);
            if ($media) {
                $alt = $matches[2] ?? $media->getDescription();
                $replace = sprintf(
                    '<img src="%s" alt="%s" title="%s"/>',
                    $this->imageBasepath . $media->getPath(),
                    $alt,
                    $alt
                );
            } else {
                $replace = '<b>UNDEFINED</b>';
            }

            return $replace;
        };

        return preg_replace_callback('/!(\d+)(?:\(([^\)]+)\))?!/m', $func, $text);
    }
}
