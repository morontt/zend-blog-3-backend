<?php

namespace Mtt\TestBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Mtt\BlogBundle\Entity\SystemParameters;

class LoadSystemParametersData extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $parameter = new SystemParameters();
        $parameter->setOptionKey('meta_description')
            ->setValue('Тестовый блог- метатег description');

        $manager->persist($parameter);
        $manager->flush();
    }
}
