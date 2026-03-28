<?php

namespace App\DataFixtures;

use App\Entity\SystemParameters;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadSystemParametersData extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $parameter = new SystemParameters();
        $parameter->setOptionKey('meta_description')
            ->setValue('Тестовый блог- метатег description');

        $manager->persist($parameter);
        $manager->flush();
    }
}
