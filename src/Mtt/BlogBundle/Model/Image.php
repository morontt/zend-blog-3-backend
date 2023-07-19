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
use Mtt\BlogBundle\Model\Resizer\AvifResizer;
use Mtt\BlogBundle\Model\Resizer\DefaultResizer;
use Mtt\BlogBundle\Model\Resizer\JpegResizer;
use Mtt\BlogBundle\Model\Resizer\PngResizer;
use Mtt\BlogBundle\Model\Resizer\WebpResizer;
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

    public function getSrcSet(): SrcSet
    {
        $srcSet = new SrcSet();
        $srcSet
            ->setOrigin($this->getSrcSetData())
            ->setWebp($this->getSrcSetData('webp'))
            ->setAvif($this->getSrcSetData('avif'))
        ;

        return $srcSet;
    }

    /**
     * @param string|null $format
     *
     * @return array
     */
    public function getSrcSetData(string $format = null): array
    {
        $width = $this->media->getWidth();
        $height = $this->media->getHeight();

        $data = [];
        $addOriginal = false;
        foreach ($this->sizes as $key => $config) {
            if ((strpos($key, 'article_') === 0) && isset($config['width'])) {
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
                if ($ext != $format && method_exists($resizer, 'convert')) {
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

        usort($data, function ($a, $b) {
            if ($a['width'] == $b['width']) {
                return 0;
            }

            return ($a['width'] < $b['width']) ? 1 : -1;
        });

        return $data;
    }

    /**
     * @param string $size
     *
     * @return string
     */
    public function getPreview(string $size, string $format = null): ?string
    {
        $newPath = $this->getPathBySize($this->media->getPath(), $size, $format);
        $fsPath = ImageManager::getUploadsDir() . '/' . $this->media->getPath();
        $fsNewPath = ImageManager::getUploadsDir() . '/' . $newPath;

        if (!file_exists($fsNewPath) && file_exists($fsPath) && is_file($fsPath)) {
            $resizer = $this->getResizer($fsPath, $format);
            try {
                $resizer->resize(
                    $fsPath,
                    $fsNewPath,
                    $this->sizes[$size]['width'] ?? 0,
                    $this->sizes[$size]['height'] ?? 0
                );
            } catch (\Throwable $e) {
                return null;
            }
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
    public function getPathBySize(string $currentPath, string $size, string $format = null): string
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

    private function getResizer(string $fsPath, string $format = null): ResizerInterface
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
