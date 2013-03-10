<?php

namespace Mtt\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Mtt\BlogBundle\Entity\PostCount;

class LoadPostCountData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $postCount = new PostCount();
        $postCount->setPost($manager->merge($this->getReference('post-1')))
            ->setComments(2);
        $manager->persist($postCount);
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