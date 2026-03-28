<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Utils\RuTransform;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoadUserData extends Fixture
{
    public const COUNT_USERS = 7;

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user
            ->setUsername('admin')
            ->setEmail('admin@example.org')
            ->setUserType(User::TYPE_ADMIN)
            ->setPassword($this->passwordHasher->hashPassword($user, 'test'))
        ;

        $manager->persist($user);
        $manager->flush();

        $this->addReference('admin-user', $user);

        $user = new User();
        $user
            ->setUsername('techbot')
            ->setEmail('techbot@example.org')
            ->setUserType(User::TYPE_ADMIN)
            ->setPassword($this->passwordHasher->hashPassword($user, 'test'))
            ->setWsseKey('SNTUd8sd2Xtf58')
        ;

        $manager->persist($user);
        $manager->flush();

        $faker = FakerFactory::create('ru_RU');
        $faker->seed(8466);

        for ($i = 0; $i < self::COUNT_USERS; $i++) {
            $user = new User();
            $user
                ->setUsername(RuTransform::ruTransform($faker->lastName))
                ->setEmail($faker->email)
                ->setPassword($this->passwordHasher->hashPassword($user, 'test'))
            ;

            if (substr($user->getUsername(), -1) === 'a') {
                $user->setGender(User::FEMALE);
            }

            $manager->persist($user);
            $this->addReference('user-' . (string)($i + 1), $user);
        }

        $manager->flush();
    }
}
