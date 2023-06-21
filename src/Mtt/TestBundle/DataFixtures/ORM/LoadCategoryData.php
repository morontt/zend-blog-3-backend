<?php

namespace Mtt\TestBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectManager as ObjectManagerInterface;
use Mtt\BlogBundle\Entity\Category;
use Mtt\BlogBundle\Utils\RuTransform;

class LoadCategoryData extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManagerInterface $manager)
    {
        $repository = $manager->getRepository(Category::class);

        $category = new Category();
        $category->setName('Программирование')
            ->setUrl(RuTransform::ruTransform('Программирование'));
        $repository->save($category);

        $this->addReference('category-1', $category);

        $category2 = new Category();
        $category2->setName('Новости')
            ->setUrl(RuTransform::ruTransform('Новости'));
        $repository->save($category2);

        $this->addReference('category-2', $category2);

        $category3 = new Category();
        $category3->setName('JavaScript')
            ->setUrl(RuTransform::ruTransform('JavaScript'))
            ->setParent($category);
        $manager->refresh($category);
        $repository->save($category3);

        $this->addReference('category-3', $category3);

        $category4 = new Category();
        $category4->setName('PHP')
            ->setUrl(RuTransform::ruTransform('PHP'))
            ->setParent($category);
        $manager->refresh($category);
        $repository->save($category4);

        $this->addReference('category-4', $category4);

        $category5 = new Category();
        $category5->setName('jQuery')
            ->setUrl(RuTransform::ruTransform('jQuery'))
            ->setParent($category3);
        $manager->refresh($category3);
        $repository->save($category5);

        $this->addReference('category-5', $category5);

        $category6 = new Category();
        $category6->setName('jQuery UI')
            ->setUrl(RuTransform::ruTransform('jQuery UI'))
            ->setParent($category5);
        $manager->refresh($category5);
        $repository->save($category6);

        $this->addReference('category-6', $category6);

        $category7 = new Category();
        $category7->setName('Database')
            ->setUrl(RuTransform::ruTransform('Database'));
        $repository->save($category7);

        $this->addReference('category-7', $category7);

        $category8 = new Category();
        $category8->setName('MySQL')
            ->setUrl(RuTransform::ruTransform('MySQL'))
            ->setParent($category7);
        $manager->refresh($category7);
        $repository->save($category8);

        $this->addReference('category-8', $category8);

        $category9 = new Category();
        $category9->setName('PostgreSQL')
            ->setUrl(RuTransform::ruTransform('PostgreSQL'))
            ->setParent($category7);
        $manager->refresh($category7);
        $repository->save($category9);

        $this->addReference('category-9', $category9);
    }
}
