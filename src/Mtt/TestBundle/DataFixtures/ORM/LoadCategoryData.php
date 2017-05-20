<?php

namespace Mtt\TestBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Mtt\BlogBundle\Entity\Category;
use Mtt\BlogBundle\Utils\RuTransform;

class LoadCategoryData extends AbstractFixture
{
    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $category = new Category();
        $category->setName('Программирование')
            ->setUrl(RuTransform::ruTransform('Программирование'));
        $manager->persist($category);
        $manager->flush();

        $this->addReference('category-prog', $category);

        $category2 = new Category();
        $category2->setName('Новости')
            ->setUrl(RuTransform::ruTransform('Новости'));
        $manager->persist($category2);
        $manager->flush();

        $this->addReference('category-news', $category2);

        $category3 = new Category();
        $category3->setName('JavaScript')
            ->setUrl(RuTransform::ruTransform('JavaScript'))
            ->setParent($category);
        $manager->persist($category3);
        $manager->flush();

        $this->addReference('category-javascript', $category3);

        $category4 = new Category();
        $category4->setName('PHP')
            ->setUrl(RuTransform::ruTransform('PHP'))
            ->setParent($category);
        $manager->persist($category4);
        $manager->flush();

        $this->addReference('category-php', $category4);

        $category5 = new Category();
        $category5->setName('jQuery')
            ->setUrl(RuTransform::ruTransform('jQuery'))
            ->setParent($category3);
        $manager->persist($category5);
        $manager->flush();

        $this->addReference('category-jquery', $category5);
    }
}
