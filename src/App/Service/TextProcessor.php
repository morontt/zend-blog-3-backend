<?php
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
    /**
     * @var MediaFileRepository
     */
    private $mediaFileRepository;

    /**
     * @var PygmentsCodeRepository
     */
    private $codeRepository;

    private ImageManager $im;

    private PictureTagBuilder $ptb;

    /**
     * @param MediaFileRepository $mediaFileRepository
     * @param PygmentsCodeRepository $codeRepository
     * @param PictureTagBuilder $ptb
     * @param ImageManager $im
     */
    public function __construct(
        MediaFileRepository $mediaFileRepository,
        PygmentsCodeRepository $codeRepository,
        PictureTagBuilder $ptb,
        ImageManager $im
    ) {
        $this->mediaFileRepository = $mediaFileRepository;
        $this->codeRepository = $codeRepository;
        $this->ptb = $ptb;
        $this->im = $im;
    }

    /**
     * @param Post $entity
     */
    public function processing(Post $entity)
    {
        $text = $this->codeSnippetProcessing($entity->getRawText());
        $text = $this->ljUserProcessing($text);
        $text = $this->imageProcessing($text);

        $entity->setText($text);
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

    private function ljUserProcessing(string $text): string
    {
        return LiveJournalHelper::replaceUserTag($text);
    }

    public function replaceImagesForArticle(array $matches)
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

    public function replaceImagesForPreview(array $matches)
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
