<?php

namespace Mtt\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Mtt\BlogBundle\Entity\Tag;
use Mtt\BlogBundle\Services\RuTransform;

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
            ->setUrl(RuTransform::ruTransform('php'));
        $manager->persist($tag);
        $manager->flush();

        $this->addReference('tag-php', $tag);

        $tag2 = new Tag();
        $tag2->setName('тест')
            ->setUrl(RuTransform::ruTransform('тест'));
        $manager->persist($tag2);
        $manager->flush();

        $this->addReference('tag-test', $tag2);

        $tag3 = new Tag();
        $tag3->setName('литература')
            ->setUrl(RuTransform::ruTransform('литература'));
        $manager->persist($tag3);
        $manager->flush();

        $this->addReference('tag-literatura', $tag3);
    }

    /**
     * @return integer
     */
    public function getOrder()
	{
		return 4;
	}
}