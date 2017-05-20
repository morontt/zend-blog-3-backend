<?php

namespace Mtt\TestBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Mtt\BlogBundle\Entity\TrackingAgent;

class LoadTrackingAgentData extends AbstractFixture
{
    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $agent = new TrackingAgent();
        $agent->setUserAgent('Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30')
            ->setBotFilter(true);

        $manager->persist($agent);
        $manager->flush();

        $this->addReference('safari', $agent);

        $agent2 = new TrackingAgent();
        $agent2->setUserAgent('Twitterbot/1.0')
            ->setBotFilter(false);

        $manager->persist($agent2);
        $manager->flush();

        $this->addReference('twitterbot', $agent2);
    }
}
