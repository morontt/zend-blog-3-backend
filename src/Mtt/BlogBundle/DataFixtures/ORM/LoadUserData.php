<?php

namespace Mtt\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Mtt\BlogBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $salt = md5(uniqid());

        $user = new User();
        $user->setUsername('admin');
        $user->setMail('admin@example.org');
        $user->setSalt($salt);
        $user->setPassword(md5('admin' . $salt));
        $user->setUserType('admin');
        $user->setTimeCreated(new \DateTime('now'));

        $manager->persist($user);
        $manager->flush();

        $this->addReference('admin-user', $user);
    }

    /**
     * @return integer
     */
    public function getOrder()
	{
		return 1;
	}
}