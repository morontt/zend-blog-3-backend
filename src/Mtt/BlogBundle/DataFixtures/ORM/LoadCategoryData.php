<?php

namespace Mtt\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Mtt\BlogBundle\Entity\Category;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $category = new Category();
        $category->setName('Программирование')
            ->setUrl('programmirovanie');
        $manager->persist($category);
        $manager->flush();

        $this->addReference('category-prog', $category);

        $category2 = new Category();
        $category2->setName('Новости')
            ->setUrl('news');
        $manager->persist($category2);
        $manager->flush();

        $this->addReference('category-news', $category2);

        $category3 = new Category();
        $category3->setName('JavaScript')
            ->setUrl('javascript')
            ->setParent($category);
        $manager->persist($category3);
        $manager->flush();

        $this->addReference('category-javascript', $category3);

        $category4 = new Category();
        $category4->setName('PHP')
            ->setUrl('php')
            ->setParent($category);
        $manager->persist($category4);
        $manager->flush();

        $this->addReference('category-php', $category4);
    }

    /**
     * @return integer
     */
    public function getOrder()
	{
		return 5;
	}
}