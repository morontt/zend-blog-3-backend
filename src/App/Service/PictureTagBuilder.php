<?php
/**
 * User: morontt
 * Date: 25.09.2024
 * Time: 21:59
 */

namespace App\Service;

use App\Entity\MediaFile;
use App\Model\Image;
use SimpleXMLElement;

class PictureTagBuilder
{
    private string $imageBasepath;

    public function __construct(string $cdnUrl)
    {
        $this->imageBasepath = $cdnUrl . ImageManager::getImageBasePath() . '/';
    }

    public function featuredPictureTag(MediaFile $entity): string
    {
        $sizes = [
            '(min-width: 48em) calc(40vw - 1.5rem)',
            'calc(100vw - 1.875rem)',
        ];

        return $this->pictureTag($entity, $sizes, $entity->getDescription(), false);
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

    private function pictureTag(MediaFile $entity, array $sizes, ?string $alt = null, $withTitle = true): string
    {
        $image = new Image($entity);
        $xml = new SimpleXMLElement('<picture/>');

        $srcSet = $image->getSrcSet();

        if ($avifSet = $srcSet->getAvif()) {
            if (count($avifSet->getItems()) > 0) {
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
        }

        if ($webpSet = $srcSet->getWebp()) {
            if (count($webpSet->getItems()) > 0) {
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
            if ($withTitle) {
                $img->addAttribute('title', $alt);
            }
        }

        $img->addAttribute('srcset', implode(', ', $srcSetStrings));
        $img->addAttribute('sizes', implode(', ', $sizes));

        return str_replace("<?xml version=\"1.0\"?>\n", '', $xml->asXML());
    }
}
