<?php

namespace Mtt\TestBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Mtt\BlogBundle\Entity\Commentator;

class LoadCommentatorData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $commentator = new Commentator();
        $commentator->setName('test-name')
            ->setMail('commentator@example.org')
            ->setWebsite('http://example.org');

        $manager->persist($commentator);
        $manager->flush();

        $this->addReference('commentator-1', $commentator);
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 9;
    }
}
