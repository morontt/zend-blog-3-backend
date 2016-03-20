<?php

namespace Mtt\TestBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Mtt\BlogBundle\Entity\Post;
use Mtt\BlogBundle\Utils\RuTransform;

class LoadPostData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $post = new Post();
        $post->setTitle('Тестовая запись')
            ->setUrl(RuTransform::ruTransform('Тестовая запись'))
            ->setCategory($manager->merge($this->getReference('category-news')))
            ->setDescription('метатег description тестовй записи')
            ->setText('<p>Тестовая запись, собственно...</p>')
            ->addTag($manager->merge($this->getReference('tag-test')));
        $manager->persist($post);
        $manager->flush();

        $post->getPostCount()->setComments(2);

        $this->addReference('post-1', $post);

        $post2 = new Post();
        $post2->setTitle('запись про PHP')
            ->setUrl(RuTransform::ruTransform('запись про PHP'))
            ->setCategory($manager->merge($this->getReference('category-php')))
            ->setDescription('метатег description тестовой записи про ПХП')
            ->setText('<p>PHP (рекурсивный акроним словосочетания PHP: Hypertext Preprocessor) - это распространенный язык программирования общего назначения с открытым исходным кодом. PHP сконструирован специально для ведения Web-разработок и его код может внедряться непосредственно в HTML.</p>')
            ->addTag($manager->merge($this->getReference('tag-php')));
        $manager->persist($post2);
        $manager->flush();

        $this->addReference('post-2', $post2);

        $post3 = new Post();
        $post3->setTitle('ещё о PHP')
            ->setUrl(RuTransform::ruTransform('ещё о PHP'))
            ->setCategory($manager->merge($this->getReference('category-php')))
            ->setDescription('description PHP')
            ->setText('<p>Ещё одна запись о PHP</p>')
            ->addTag($manager->merge($this->getReference('tag-php')));
        $manager->persist($post3);
        $manager->flush();

        $this->addReference('post-3', $post3);

        $post4 = new Post();
        $post4->setTitle('JavaScript, хоть и jQuery')
            ->setUrl(RuTransform::ruTransform('JavaScript, хоть и jQuery'))
            ->setCategory($manager->merge($this->getReference('category-jquery')))
            ->setDescription('description-JavaScript')
            ->setText('<p>JavaScript - прототипно-ориентированный сценарный язык программирования. Является диалектом языка ECMAScript</p><!-- cut --><p>Параграф под катом</p>')
            ->addTag($manager->merge($this->getReference('tag-javascript')));
        $manager->persist($post4);
        $manager->flush();

        $this->addReference('post-4', $post4);
    }

    /**
     * @return integer
     */
    public function getOrder()
    {
        return 5;
    }
}
