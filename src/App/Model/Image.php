<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 03.05.18
 * Time: 0:01
 */

namespace App\Model;

use App\Entity\MediaFile;
use App\Entity\Post;
use App\Model\Resizer\AvifResizer;
use App\Model\Resizer\DefaultResizer;
use App\Model\Resizer\JpegResizer;
use App\Model\Resizer\PngResizer;
use App\Model\Resizer\WebpResizer;
use App\Service\ImageManager;
use DateTime;
use Imagick;
use ImagickException;
use RuntimeException;

/**
 * @method int getId()
 * @method Post|null getPost()
 * @method string getPath()
 * @method string getOriginalFileName()
 * @method int getFileSize()
 * @method string|null getDescription()
 * @method DateTime getTimeCreated()
 * @method DateTime getLastUpdate()
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
            'height' => 60,
        ],
        'article_864' => [
            'width' => 864,
        ],
        'article_624' => [
            'width' => 624,
        ],
        'article_444' => [
            'width' => 448,
        ],
        'article_320' => [
            'width' => 320,
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
     * @param string|null $format
     *
     * @return array
     */
    public function getSrcSetData(?string $format = null): array
    {
        $width = $this->media->getWidth();
        $height = $this->media->getHeight();

        $data = [];
        $addOriginal = false;
        foreach ($this->sizes as $key => $config) {
            if (isset($config['width']) && (strpos($key, 'article_') === 0)) {
                if ($config['width'] < $width) {
                    if ($newPath = $this->getPreview($key, $format)) {
                        $data[] = [
                            'width' => $config['width'],
                            'height' => (int)round(1.0 * $height * $config['width'] / $width),
                            'path' => $newPath,
                        ];
                    }
                } else {
                    $addOriginal = true;
                }
            }
        }

        if ($addOriginal) {
            if ($format) {
                $ext = pathinfo($this->media->getPath(), PATHINFO_EXTENSION);
                $resizer = $this->getResizer($this->media->getPath(), $format);
                if ($ext !== $format && method_exists($resizer, 'convert')) {
                    $newPath = $resizer->convert($this->media->getPath(), ImageManager::getUploadsDir());
                    $data[] = [
                        'width' => $width,
                        'height' => $height,
                        'path' => $newPath,
                    ];
                }
            } else {
                $data[] = [
                    'width' => $width,
                    'height' => $height,
                    'path' => $this->media->getPath(),
                ];
            }
        }

        $data = array_map(function (array $item) {
            $item['length'] = (int)filesize(ImageManager::getUploadsDir() . '/' . $item['path']);

            return $item;
        }, $data);

        usort($data, function ($a, $b) {
            if ($a['width'] === $b['width']) {
                return 0;
            }

            return ($a['width'] < $b['width']) ? 1 : -1;
        });

        return $data;
    }

    /**
     * @param string $size
     * @param string|null $format
     *
     * @return string|null
     */
    public function getPreview(string $size, ?string $format = null): ?string
    {
        if ($this->media->isImage()) {
            $mediaPath = $this->media->getPath();
            $fsPath = ImageManager::getUploadsDir() . '/' . $mediaPath;
        } else {
            $mediaPath = 'not_image.png';
            $fsPath = APP_WEB_DIR . '/img/' . $mediaPath;
        }

        $newPath = $this->getPathBySize($mediaPath, $size, $format);
        $fsNewPath = ImageManager::getUploadsDir() . '/' . $newPath;

        if (!file_exists($fsNewPath) && file_exists($fsPath) && is_file($fsPath)) {
            $resizer = $this->getResizer($fsPath, $format);
            $resizer->resize(
                $fsPath,
                $fsNewPath,
                $this->sizes[$size]['width'] ?? 0,
                $this->sizes[$size]['height'] ?? 0
            );
        }

        return $newPath;
    }

    /**
     * @param string $currentPath
     * @param string $size
     * @param string|null $format
     *
     * @return string
     */
    public function getPathBySize(string $currentPath, string $size, ?string $format = null): string
    {
        if (!isset($this->sizes[$size])) {
            throw new RuntimeException('undefined size');
        }

        $pathInfo = pathinfo($currentPath);
        if ($pathInfo['dirname'] === '.') {
            $dirNamePrefix = '';
        } else {
            $dirNamePrefix = $pathInfo['dirname'] . '/';
        }

        $ext = $format ?? $pathInfo['extension'];

        $res = sprintf(
            '%s%s%s.%s',
            $pathInfo['filename'],
            isset($this->sizes[$size]['width']) ? '_' . $this->sizes[$size]['width'] . 'w' : '',
            isset($this->sizes[$size]['height']) ? '_' . $this->sizes[$size]['height'] . 'h' : '',
            $ext
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
            $orientation = $image->getImageOrientation();
            $image->clear();

            if (in_array(
                $orientation,
                [
                    Imagick::ORIENTATION_LEFTTOP,
                    Imagick::ORIENTATION_RIGHTTOP,
                    Imagick::ORIENTATION_RIGHTBOTTOM,
                    Imagick::ORIENTATION_LEFTBOTTOM,
                ],
                true
            )) {
                $obj->width = $geometry['height'];
                $obj->height = $geometry['width'];
            } else {
                $obj->width = $geometry['width'];
                $obj->height = $geometry['height'];
            }
        } catch (ImagickException $e) {
            // TODO add error to logger
        }

        return $obj;
    }

    /**
     * @param $method
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return call_user_func_array([$this->media, $method], $arguments);
    }

    private function getResizer(string $fsPath, ?string $format = null): ResizerInterface
    {
        switch ($format ?? strtolower(pathinfo($fsPath, PATHINFO_EXTENSION))) {
            case 'jpeg':
            case 'jpg':
                return new JpegResizer();
            case 'png':
                return new PngResizer();
            case 'webp':
                return new WebpResizer();
            case 'avif':
                return new AvifResizer();
        }

        return new DefaultResizer();
    }
}
