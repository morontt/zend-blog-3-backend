<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 10.04.16
 * Time: 21:56
 */

namespace Mtt\TestBundle\DataFixtures\ORM;

use App\Entity\MediaFile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectManager as ObjectManagerInterface;

class LoadMediaFileData extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManagerInterface $manager)
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
