<?php

namespace Mtt\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Mtt\BlogBundle\Entity\Comment;

class LoadCommentData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $comment = new Comment();
        $comment->setText('Тестовый комментарий')
            ->setIpAddress('94.231.112.91')
            ->setPost($manager->merge($this->getReference('post-1')))
            ->setCommentator($manager->merge($this->getReference('commentator-1')))
            ->setTimeCreated(new \DateTime('now'));

        $manager->persist($comment);
        $manager->flush();

        $comment2 = new Comment();
        $comment2->setText('Ответ на тестовый комментарий')
            ->setIpAddress('62.72.188.111')
            ->setPost($manager->merge($this->getReference('post-1')))
            ->setUser($manager->merge($this->getReference('admin-user')))
            ->setParent($comment)
            ->setTimeCreated(new \DateTime('now'));

        $manager->persist($comment2);
        $manager->flush();
    }

    /**
     * @return integer
     */
    public function getOrder()
    {
        return 10;
    }
}
