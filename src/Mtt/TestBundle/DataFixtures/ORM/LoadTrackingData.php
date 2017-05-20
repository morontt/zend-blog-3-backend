<?php

namespace Mtt\TestBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Mtt\BlogBundle\Entity\Tracking;

class LoadTrackingData extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $tracking = new Tracking();
        $tracking->setTrackingAgent($manager->merge($this->getReference('safari')))
            ->setIpAddress('127.0.0.1')
            ->setPost($manager->merge($this->getReference('post-1')));

        $manager->persist($tracking);
        $manager->flush();

        $tracking2 = new Tracking();
        $tracking2->setTrackingAgent($manager->merge($this->getReference('twitterbot')))
            ->setIpAddress('173.199.116.91')
            ->setPost($manager->merge($this->getReference('post-1')));

        $manager->persist($tracking2);
        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            LoadTrackingAgentData::class,
        ];
    }
}
