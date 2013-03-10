<?php

namespace Mtt\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Mtt\BlogBundle\Entity\Post;
use Mtt\BlogBundle\Services\RuTransform;

class LoadPostData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $post = new Post();
        $post->setTitle('Тестовая запись')
            ->setUrl(RuTransform::ruTransform('Тестовая запись'))
            ->setCategory($manager->merge($this->getReference('category-news')))
            ->setUser($manager->merge($this->getReference('admin-user')))
            ->setDescription('метатег description тестовй записи')
            ->setText('Тестовая запись, собственно...')
            ->addTag($manager->merge($this->getReference('tag-test')))
            ->setTimeCreated(new \DateTime('now'));
        $manager->persist($post);
        $manager->flush();

        $this->addReference('post-1', $post);
    }

    /**
     * @return integer
     */
    public function getOrder()
	{
		return 5;
	}
}