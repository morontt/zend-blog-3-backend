<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 09.05.16
 * Time: 0:50
 */

namespace Mtt\BlogBundle\Cron\Daily;

use Aws\S3\S3Client;
use Doctrine\ORM\EntityManager;
use Dropbox\Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
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
     * @var Filesystem
     */
    protected $amazonFs;


    /**
     * ImagesBackup constructor.
     * @param EntityManager $em
     * @param array $options
     */
    public function __construct(EntityManager $em, array $options)
    {
        $this->em = $em;

        $amazonClient = new S3Client([
            'region' => $options['region'],
            'version' => 'latest',
            'credentials' => [
                'key'    => $options['key'],
                'secret' => $options['secret'],
            ],
        ]);

        $this->amazonFs = new Filesystem(new AwsS3Adapter($amazonClient, $options['bucket']));
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
                        $mountManager->read('amazon://' . $image->getPath())
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
        $dropboxFs = new Filesystem(new DropboxAdapter($dropboxClient));

        return new MountManager([
            'amazon' => $this->amazonFs,
            'dropbox' => $dropboxFs,
        ]);
    }
}
