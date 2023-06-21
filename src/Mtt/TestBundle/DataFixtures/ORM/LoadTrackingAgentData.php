<?php

namespace Mtt\TestBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectManager as ObjectManagerInterface;
use Mtt\BlogBundle\Entity\TrackingAgent;

class LoadTrackingAgentData extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManagerInterface $manager)
    {
        $agent = new TrackingAgent();
        $agent->setUserAgent('Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30')
            ->setBot(true);

        $manager->persist($agent);
        $manager->flush();

        $this->addReference('safari', $agent);

        $agent2 = new TrackingAgent();
        $agent2->setUserAgent('Twitterbot/1.0')
            ->setBot(false);

        $manager->persist($agent2);
        $manager->flush();

        $this->addReference('twitterbot', $agent2);
    }
}
