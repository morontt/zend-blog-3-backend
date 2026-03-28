<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\Tracking;
use App\Entity\TrackingAgent;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadTrackingData extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $tracking = new Tracking();
        $tracking->setTrackingAgent($this->getReference('safari', TrackingAgent::class))
            ->setIpAddress('127.0.0.1')
            ->setPost($this->getReference('post-1', Post::class));

        $manager->persist($tracking);
        $manager->flush();

        $tracking2 = new Tracking();
        $tracking2->setTrackingAgent($this->getReference('twitterbot', TrackingAgent::class))
            ->setIpAddress('173.199.116.91')
            ->setPost($this->getReference('post-1', Post::class));

        $manager->persist($tracking2);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            LoadTrackingAgentData::class,
            LoadPostData::class,
        ];
    }
}
