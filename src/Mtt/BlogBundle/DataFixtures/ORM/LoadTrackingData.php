<?php

namespace Mtt\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Mtt\BlogBundle\Entity\Tracking;

class LoadTrackingData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $tracking = new Tracking();
        $tracking->setTrackingAgent($manager->merge($this->getReference('safari')))
            ->setIpAddress('127.0.0.1')
            ->setPost($manager->merge($this->getReference('post-1')))
            ->setTimeCreated(new \DateTime('now'));

        $manager->persist($tracking);
        $manager->flush();

        $tracking2 = new Tracking();
        $tracking2->setTrackingAgent($manager->merge($this->getReference('twitterbot')))
            ->setIpAddress('173.199.116.91')
            ->setPost($manager->merge($this->getReference('post-1')))
            ->setTimeCreated(new \DateTime('now'));

        $manager->persist($tracking2);
        $manager->flush();
    }

    /**
     * @return integer
     */
    public function getOrder()
	{
		return 8;
	}
}