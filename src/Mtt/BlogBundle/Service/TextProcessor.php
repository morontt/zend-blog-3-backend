<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 10.04.16
 * Time: 17:29
 */

namespace Mtt\BlogBundle\Service;

use Mtt\BlogBundle\Entity\Post;
use Mtt\BlogBundle\Entity\Repository\MediaFileRepository;
use Mtt\BlogBundle\Entity\Repository\PygmentsCodeRepository;

class TextProcessor
{
    /**
     * @var MediaFileRepository
     */
    private $mediaFileRepository;

    /**
     * @var PygmentsCodeRepository
     */
    private $codeRepository;

    private ImageManager $im;

    /**
     * @param MediaFileRepository $mediaFileRepository
     * @param PygmentsCodeRepository $codeRepository
     */
    public function __construct(
        MediaFileRepository $mediaFileRepository,
        PygmentsCodeRepository $codeRepository,
        ImageManager $im
    ) {
        $this->mediaFileRepository = $mediaFileRepository;
        $this->codeRepository = $codeRepository;
        $this->im = $im;
    }

    /**
     * @param Post $entity
     */
    public function processing(Post $entity)
    {
        $text = $this->codeSnippetProcessing($entity->getRawText());

        $entity->setText($this->imageProcessing($text));
        $entity->setPreview($this->preview($text));
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
            [$this, 'replaceImagesForArticle'],
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
            [$this, 'replaceImagesForPreview'],
            $preview[0]
        );
    }

    /**
     * @param string $text
     *
     * @return string
     */
    private function codeSnippetProcessing(string $text): string
    {
        $func = function (array $matches) {
            $code = $this->codeRepository->find((int)$matches['id']);
            if ($code) {
                $replace = $code->getSourceHtml();
            } else {
                $replace = '<b>UNDEFINED CODE SNIPPET</b>';
            }

            return $replace;
        };

        return preg_replace_callback(
            '/!<code>(?<id>\d+)!/m',
            $func,
            $text
        );
    }

    public function replaceImagesForArticle(array $matches)
    {
        return $this->replaceImages($matches);
    }

    public function replaceImagesForPreview(array $matches)
    {
        return $this->replaceImages($matches, false);
    }

    private function replaceImages(array $matches, bool $withDefault = true)
    {
        $media = $this->mediaFileRepository->find((int)$matches['id']);
        if ($media) {
            if ($withDefault || !$media->isDefaultImage()) {
                $alt = $matches['alt'] ?? $media->getDescription();
                $replace = $this->im->pictureTag($media, $alt);
            } else {
                $replace = '';
            }
        } else {
            $replace = '<b>UNDEFINED</b>';
        }

        return $replace;
    }
}
