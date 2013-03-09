<?php

namespace Mtt\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Mtt\BlogBundle\Entity\Tag;

class LoadTagData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $tag = new Tag();
        $tag->setName('php')
            ->setUrl('php');
        $manager->persist($tag);
        $manager->flush();

        $this->addReference('tag-php', $tag);

        $tag2 = new Tag();
        $tag2->setName('тест')
            ->setUrl('test');
        $manager->persist($tag2);
        $manager->flush();

        $this->addReference('tag-test', $tag2);

        $tag3 = new Tag();
        $tag3->setName('литература')
            ->setUrl('literatura');
        $manager->persist($tag3);
        $manager->flush();

        $this->addReference('tag-literatura', $tag3);
    }

    /**
     * @return integer
     */
    public function getOrder()
	{
		return 6;
	}
}