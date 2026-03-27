<?php

namespace Mtt\TestBundle\DataFixtures\ORM;

use App\Entity\Tracking;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectManager as ObjectManagerInterface;

class LoadTrackingData extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManagerInterface $manager)
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
