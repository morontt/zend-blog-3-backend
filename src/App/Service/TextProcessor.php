<?php

declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 10.04.16
 * Time: 17:29
 */

namespace App\Service;

use App\Entity\Post;
use App\Repository\MediaFileRepository;
use App\Repository\PygmentsCodeRepository;
use App\Utils\LiveJournalHelper;

class TextProcessor
{
    public function __construct(
        private MediaFileRepository $mediaFileRepository,
        private PygmentsCodeRepository $codeRepository,
        private PictureTagBuilder $ptb,
        private ImageManager $im,
    ) {
        $this->mediaFileRepository = $mediaFileRepository;
        $this->codeRepository = $codeRepository;
        $this->ptb = $ptb;
        $this->im = $im;
    }

    /**
     * @param Post $entity
     */
    public function processing(Post $entity): void
    {
        $text = $this->codeSnippetProcessing($entity->getRawText());
        $text = $this->ljUserProcessing($text);

        $entity->setText($this->imageProcessing($text));
        $entity->setPreview($this->imageProcessingPreview($text));
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
    private function imageProcessingPreview(string $text): string
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

    private function ljUserProcessing(string $text): string
    {
        return LiveJournalHelper::replaceUserTag($text);
    }

    /**
     * @param array<string, string> $matches
     */
    public function replaceImagesForArticle(array $matches): string
    {
        $media = $this->mediaFileRepository->find((int)$matches['id']);
        if ($media) {
            $alt = $matches['alt'] ?? $media->getDescription();
            $replace = $this->ptb->articlePictureTag($media, $alt);

            if ($media->getWidth() > 864) {
                $replace = sprintf(
                    '<a class="anima-image-popup" href="%s">%s</a>',
                    $this->im->cdnImagePath() . $media->getPath(),
                    $replace
                );
            }
        } else {
            $replace = '<b>UNDEFINED</b>';
        }

        return $replace;
    }

    /**
     * @param array<string, string> $matches
     */
    public function replaceImagesForPreview(array $matches): string
    {
        $media = $this->mediaFileRepository->find((int)$matches['id']);
        if ($media) {
            if (!$media->isDefaultImage()) {
                $alt = $matches['alt'] ?? $media->getDescription();
                $replace = $this->ptb->previewPictureTag($media, $alt);
            } else {
                $replace = '';
            }
        } else {
            $replace = '<b>UNDEFINED</b>';
        }

        return $replace;
    }
}
