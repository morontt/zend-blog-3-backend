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
use Mtt\BlogBundle\Entity\Post;
use Mtt\BlogBundle\Service\ImageManager;

/**
 * @method int getId()
 * @method Post|null getPost()
 * @method string getPath()
 * @method string getOriginalFileName()
 * @method int getFileSize()
 * @method string|null getDescription()
 * @method \DateTime getTimeCreated()
 * @method \DateTime getLastUpdate()
 * @method bool isDefaultImage()
 * @method int|null getWidth()
 * @method int|null getHeight()
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
        'article_864' => [
            'width' => 864,
            'height' => 0,
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

        $pathInfo = pathinfo($currentPath);
        if ($pathInfo['dirname'] === '.') {
            $dirNamePrefix = '';
        } else {
            $dirNamePrefix = $pathInfo['dirname'] . '/';
        }

        $res = sprintf(
            '%s%s%s.%s',
            $pathInfo['filename'],
            $this->sizes[$size]['width'] ? '_' . $this->sizes[$size]['width'] . 'w' : '',
            $this->sizes[$size]['height'] ? '_' . $this->sizes[$size]['height'] . 'h' : '',
            $pathInfo['extension']
        );

        return $dirNamePrefix . $res;
    }

    public function getImageGeometry(): ImageGeometry
    {
        $fsPath = ImageManager::getUploadsDir() . '/' . $this->media->getPath();
        $obj = new ImageGeometry();

        try {
            $image = new Imagick($fsPath);
            $geometry = $image->getImageGeometry();
            $image->clear();

            $obj->width = $geometry['width'];
            $obj->height = $geometry['height'];
        } catch (\ImagickException $e) {
        }

        return $obj;
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
