<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 10.04.16
 * Time: 17:29
 */

namespace Mtt\BlogBundle\Service;

use Doctrine\ORM\EntityManager;

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
     * @param EntityManager $em
     * @param $imageBasepath
     */
    public function __construct(EntityManager $em, $imageBasepath)
    {
        $this->em = $em;
        $this->imageBasepath = $imageBasepath . '/';
    }

    /**
     * @param $text
     * @return string
     */
    public function processing($text)
    {
        do {
            $r = $this->imageSearchAndReplace($text);
        } while($r);

        return $text;
    }

    /**
     * @param string $text
     * @return bool
     */
    protected function imageSearchAndReplace(&$text)
    {
        $matches = [];
        $result = false;
        if (preg_match('/!(\d+)!/m', $text, $matches)) {
            $imgId = (int)$matches[1];
            $media = $this->em->getRepository('MttBlogBundle:MediaFile')->find($imgId);
            if ($media) {
                $replace = sprintf(
                    '<img src="%s" alt="%s" title="%s"/>',
                    $this->imageBasepath . $media->getPath(),
                    $media->getDescription(),
                    $media->getDescription()
                );
            } else {
                $replace = '<b>UNDEFINED</b>';
            }

            $result = true;
            $text = str_replace('!' . $imgId . '!', $replace, $text);
        }

        return $result;
    }
}
