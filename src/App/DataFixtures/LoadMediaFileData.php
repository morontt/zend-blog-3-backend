<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 10.04.16
 * Time: 21:56
 */

namespace App\DataFixtures;

use App\Entity\MediaFile;
use App\Model\Image;
use App\Service\ImageManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadMediaFileData extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $file = new MediaFile();

        $fileName = 'vintage_robot.jpg';

        $file
            ->setPath($fileName)
            ->setDescription('file for testing')
            ->setFileSize(filesize(ImageManager::getUploadsDir() . '/' . $fileName))
            ->setDefaultImage(true)
        ;

        $image = new Image($file);
        $geometry = $image->getImageGeometry();

        $file
            ->setWidth($geometry->width)
            ->setHeight($geometry->height)
        ;

        $manager->persist($file);
        $manager->flush();

        $this->addReference('file-1', $file);
    }
}
