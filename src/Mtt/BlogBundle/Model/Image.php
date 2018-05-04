<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 03.05.18
 * Time: 0:01
 */

namespace Mtt\BlogBundle\Model;

use Imagick;
use Mtt\BlogBundle\Entity\MediaFile;
use Mtt\BlogBundle\Service\ImageManager;

/**
 * @method int getId()
 * @method \Mtt\BlogBundle\Entity\Post|null getPost()
 * @method string getPath()
 * @method string getOriginalFileName()
 * @method int getFileSize()
 * @method string|null getDescription()
 * @method \DateTime getTimeCreated()
 * @method \DateTime getLastUpdate()
 * @method bool isDefaultImage()
 */
class Image
{
    /**
     * @var array
     */
    protected $sizes = [
        'admin_list' => [
            'width' => 0,
            'height' => 60,
        ],
    ];

    /**
     * @var MediaFile
     */
    protected $media;

    /**
     * @param MediaFile $media
     */
    public function __construct(MediaFile $media)
    {
        $this->media = $media;
    }

    /**
     * @param string $size
     *
     * @return string
     */
    public function getPreview(string $size): ?string
    {
        $newPath = $this->getPathBySize($this->media->getPath(), $size);
        $fsPath = ImageManager::getUploadsDir() . '/' . $this->media->getPath();
        $fsNewPath = ImageManager::getUploadsDir() . '/' . $newPath;

        if (!file_exists($fsNewPath) && file_exists($fsPath) && is_file($fsPath)) {
            try {
                $image = new Imagick($fsPath);
                $image->thumbnailImage($this->sizes[$size]['width'], $this->sizes[$size]['height']);

                $image->writeImage($fsNewPath);
                $image->clear();
            } catch (\ImagickException $e) {
                return null;
            }
        }

        return $newPath;
    }

    /**
     * @param string $currentPath
     * @param string $size
     *
     * @return string
     */
    public function getPathBySize(string $currentPath, string $size): string
    {
        if (!isset($this->sizes[$size])) {
            throw new \RuntimeException('undefined size');
        }

        $pathinfo = pathinfo($currentPath);

        if ($pathinfo['dirname'] === '.') {
            $res = sprintf(
                '%d_%d_%s',
                $this->sizes[$size]['width'] ?: 0,
                $this->sizes[$size]['height'] ?: 0,
                $pathinfo['basename']
            );
        } else {
            $res = sprintf(
                '%s/%d_%d_%s',
                $pathinfo['dirname'],
                $this->sizes[$size]['width'] ?: 0,
                $this->sizes[$size]['height'] ?: 0,
                $pathinfo['basename']
            );
        }

        return $res;
    }

    /**
     * @param string $method
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return call_user_func_array([$this->media, $method], $arguments);
    }
}
