<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 10.04.16
 * Time: 21:56
 */

namespace Mtt\TestBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Mtt\BlogBundle\Entity\MediaFile;

class LoadMediaFileData extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $file = new MediaFile();

        $file
            ->setPath('gravatar.png')
            ->setDescription('file for testing')
            ->setFileSize(189764)
            ->setDefaultImage(true)
        ;

        $manager->persist($file);
        $manager->flush();

        $this->addReference('file-1', $file);
    }
}
