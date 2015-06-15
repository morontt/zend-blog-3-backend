<?php

namespace Mtt\TestBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Mtt\BlogBundle\Entity\PostCount;

class LoadPostCountData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $postCount = new PostCount();
        $postCount->setPost($manager->merge($this->getReference('post-1')))
            ->setComments(2);
        $manager->persist($postCount);
        $manager->flush();

        $postCount2 = new PostCount();
        $postCount2->setPost($manager->merge($this->getReference('post-2')));
        $manager->persist($postCount2);
        $manager->flush();

        $postCount3 = new PostCount();
        $postCount3->setPost($manager->merge($this->getReference('post-3')));
        $manager->persist($postCount3);
        $manager->flush();

        $postCount4 = new PostCount();
        $postCount4->setPost($manager->merge($this->getReference('post-4')));
        $manager->persist($postCount4);
        $manager->flush();
    }

    /**
     * @return integer
     */
    public function getOrder()
    {
        return 6;
    }
}
