<?php

namespace Mtt\TestBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Mtt\BlogBundle\Entity\Commentator;

class LoadCommentatorData extends Fixture
{
    const COUNT_COMMENTATORS = 24;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $commentator = new Commentator();
        $commentator
            ->setName('test-name')
            ->setEmail('commentator@example.org')
            ->setWebsite('http://example.org')
        ;

        $manager->persist($commentator);
        $manager->flush();

        $this->addReference('commentator-1', $commentator);

        $faker = FakerFactory::create('ru_RU');
        $faker->seed(164504);

        for ($i = 0; $i < self::COUNT_COMMENTATORS; $i++) {
            $commentator = new Commentator();

            $commentator->setName($faker->firstName);
            if ($faker->numberBetween(0, 100) < 60) {
                $commentator->setEmail($faker->email);
            }
            if ($faker->numberBetween(0, 100) < 30) {
                $commentator->setWebsite($faker->domainName);
            }

            $manager->persist($commentator);
            $manager->flush();

            $this->addReference('commentator-' . (string)($i + 2), $commentator);
        }
    }
}
