<?php

namespace Mtt\TestBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Mtt\BlogBundle\Utils\RuTransform;
use Mtt\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData extends AbstractFixture implements ContainerAwareInterface
{
    const COUNT_USERS = 10;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($user);

        $user
            ->setUsername('admin')
            ->setEmail('morontt@gmail.com')
            ->setPassword($encoder->encodePassword('test', $user->getSalt()))
        ;

        $manager->persist($user);
        $manager->flush();

        $this->addReference('admin-user', $user);

        $faker = FakerFactory::create('ru_RU');
        $faker->seed(8466);

        for ($i = 0; $i < self::COUNT_USERS; $i++) {
            $user = new User();
            $user
                ->setUsername(RuTransform::ruTransform($faker->lastName))
                ->setEmail($faker->email)
                ->setPassword($encoder->encodePassword('test', $user->getSalt()))
            ;

            $manager->persist($user);
            $this->addReference('user-' . (string)($i + 1), $user);
        }

        $manager->flush();
    }
}
