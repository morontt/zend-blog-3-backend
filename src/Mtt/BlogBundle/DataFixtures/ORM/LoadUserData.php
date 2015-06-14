<?php

namespace Mtt\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Mtt\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($user);

        $user->setUsername('admin')
            ->setMail('morontt@gmail.com')
            ->setPassword($encoder->encodePassword('test', $user->getSalt()))
            ->setUserType('admin');

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
