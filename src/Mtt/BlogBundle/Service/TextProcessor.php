<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 10.04.16
 * Time: 17:29
 */

namespace Mtt\BlogBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Mtt\BlogBundle\Entity\MediaFile;
use Mtt\BlogBundle\Entity\Post;

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
     * @param EntityManagerInterface $em
     * @param string $cdn
     */
    public function __construct(EntityManagerInterface $em, string $cdn)
    {
        $this->em = $em;
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
        $fuse = 0;

        do {
            $r = $this->imageSearchAndReplace($text);
            $fuse++;
        } while ($r && $fuse < 100);

        return $text;
    }

    /**
     * @param string $text
     *
     * @return bool
     */
    protected function imageSearchAndReplace(&$text)
    {
        $matches = [];
        $result = false;
        if (preg_match('/!(\d+)(?:\(([^\)]+)\))?!/m', $text, $matches)) {
            $imgId = (int)$matches[1];
            /* @var MediaFile $media */
            $media = $this->em->getRepository('MttBlogBundle:MediaFile')->find($imgId);
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

            $result = true;
            $text = preg_replace('/!' . $imgId . '(?:\([^\)]+\))?!/m', $replace, $text);
        }

        return $result;
    }
}
