<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 10.04.16
 * Time: 21:56
 */

namespace App\DataFixtures;

use App\Entity\MediaFile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadMediaFileData extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $file = new MediaFile();

        $file
            ->setPath('vintage_robot.jpg')
            ->setDescription('file for testing')
            ->setFileSize(189764)
            ->setDefaultImage(true)
        ;

        $manager->persist($file);
        $manager->flush();

        $this->addReference('file-1', $file);
    }
}
