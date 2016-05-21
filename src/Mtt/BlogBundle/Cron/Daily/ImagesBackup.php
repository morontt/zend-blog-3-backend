<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 09.05.16
 * Time: 0:50
 */

namespace Mtt\BlogBundle\Cron\Daily;

use Doctrine\ORM\EntityManager;
use Dropbox\Client;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Dropbox\DropboxAdapter;
use League\Flysystem\Filesystem;
use League\Flysystem\MountManager;
use Mtt\BlogBundle\Cron\CronServiceInterface;
use Mtt\BlogBundle\Entity\SystemParameters;

class ImagesBackup implements CronServiceInterface
{
    /**
     * @var EntityManager
     */
    protected $em;


    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function run()
    {
        $images = $this->em->getRepository('MttBlogBundle:MediaFile')->getNotBackuped();

        if (count($images)) {
            $mountManager = $this->getMountManager();
            if ($mountManager) {
                foreach ($images as $image) {
                    $mountManager->put(
                        'dropbox://blog_images/' . $image->getPath(),
                        $mountManager->read('local://' . $image->getPath())
                    );

                    $image->setBackuped(true);
                    $this->em->flush();
                }
            }
        }
    }

    /**
     * @return MountManager|null
     */
    protected function getMountManager()
    {
        /* @var SystemParameters $sp */
        $sp = $this->em->getRepository('MttBlogBundle:SystemParameters')
            ->findOneByOptionKey(SystemParameters::DROPBOX_TOKEN);

        if (!$sp) {
            return null;
        }

        $tokenData = unserialize($sp->getValue());
        $dropboxClient = new Client($tokenData['access_token'], 'ZendBlog-Backuper/0.1');

        return new MountManager([
            'local' => new Filesystem(new Local(realpath(__DIR__ . '/../../../../../web/uploads'))),
            'dropbox' => new Filesystem(new DropboxAdapter($dropboxClient)),
        ]);
    }
}
