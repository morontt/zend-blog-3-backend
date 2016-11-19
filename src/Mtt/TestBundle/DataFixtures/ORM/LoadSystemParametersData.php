<?php

namespace Mtt\TestBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Mtt\BlogBundle\Entity\SystemParameters;

class LoadSystemParametersData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $parameter = new SystemParameters();
        $parameter->setOptionKey('meta_description')
            ->setValue('Тестовый блог- метатег description');

        $manager->persist($parameter);
        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }
}
