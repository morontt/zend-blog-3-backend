<?php

namespace Mtt\TestBundle\DataFixtures\ORM;

use App\Entity\Tag;
use App\Utils\RuTransform;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectManager as ObjectManagerInterface;
use Faker\Factory as FakerFactory;

class LoadTagData extends Fixture
{
    public const COUNT_TAGS = 60;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManagerInterface $manager)
    {
        $faker = FakerFactory::create('ru_RU');
        $faker->seed(1022);

        for ($i = 0; $i < self::COUNT_TAGS; $i++) {
            $tag = new Tag();

            $tagName = $faker->unique()->word;
            $tag
                ->setName($tagName)
                ->setUrl(RuTransform::ruTransform($tagName))
            ;

            $manager->persist($tag);
            $this->addReference('tag-' . $i, $tag);
        }

        $manager->flush();

        $tag = new Tag();
        $tag->setName('php')
            ->setUrl(RuTransform::ruTransform('php'));
        $manager->persist($tag);
        $manager->flush();

        $this->addReference('tag-php', $tag);

        $tag2 = new Tag();
        $tag2->setName('тест')
            ->setUrl(RuTransform::ruTransform('тест'));
        $manager->persist($tag2);
        $manager->flush();

        $this->addReference('tag-test', $tag2);

        $tag3 = new Tag();
        $tag3->setName('литература')
            ->setUrl(RuTransform::ruTransform('литература'));
        $manager->persist($tag3);
        $manager->flush();

        $this->addReference('tag-literatura', $tag3);

        $tag4 = new Tag();
        $tag4->setName('javascript')
            ->setUrl(RuTransform::ruTransform('javascript'));
        $manager->persist($tag4);
        $manager->flush();

        $this->addReference('tag-javascript', $tag4);
    }
}
