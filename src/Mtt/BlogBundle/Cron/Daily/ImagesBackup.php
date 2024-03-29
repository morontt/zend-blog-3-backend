<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 09.05.16
 * Time: 0:50
 */

namespace Mtt\BlogBundle\Cron\Daily;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Mtt\BlogBundle\Cron\DailyCronServiceInterface;
use Mtt\BlogBundle\Service\DropboxService;
use Mtt\BlogBundle\Service\ImageManager;
use Mtt\BlogBundle\Service\SystemParametersStorage;

class ImagesBackup implements DailyCronServiceInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var int
     */
    protected $countImported = 0;

    /**
     * @var SystemParametersStorage
     */
    protected $storage;

    /**
     * @var DropboxService
     */
    protected $dropbox;

    /**
     * @param EntityManagerInterface $em
     * @param DropboxService $dropbox
     */
    public function __construct(EntityManagerInterface $em, DropboxService $dropbox)
    {
        $this->em = $em;
        $this->dropbox = $dropbox;
    }

    public function run()
    {
        $images = $this->em->getRepository('MttBlogBundle:MediaFile')->getNotBackuped();

        if (count($images)) {
            foreach ($images as $image) {
                $this->dropbox->upload(
                    ImageManager::getUploadsDir() . '/' . $image->getPath(),
                    '/blog_images/' . $image->getPath()
                );

                $image->setBackuped(true);
                $this->em->flush();

                $this->countImported++;
            }
        }
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        $message = 'Nothing';
        if ($this->countImported == 1) {
            $message = '1 new image';
        } elseif ($this->countImported > 1) {
            $message = $this->countImported . ' new images';
        }

        return $message;
    }

    /**
     * @return bool|string
     */
    protected function getImagesDir()
    {
        return APP_WEB_DIR . '/uploads';
    }
}
