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
     * TextProcessor constructor.
     *
     * @param EntityManager $em
     * @param $imageBasepath
     */
    public function __construct(EntityManager $em, $imageBasepath)
    {
        $this->em = $em;
        $this->imageBasepath = $imageBasepath . '/';
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
            $media = $this->em->getRepository('MttBlogBundle:MediaFile')->find($imgId);
            if ($media) {
                $alt = isset($matches[2]) ? $matches[2] : $media->getDescription();
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
