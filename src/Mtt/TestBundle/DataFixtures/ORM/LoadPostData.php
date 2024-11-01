<?php

namespace Mtt\TestBundle\DataFixtures\ORM;

use App\Entity\Post;
use App\Service\TextProcessor;
use App\Utils\RuTransform;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectManager as ObjectManagerInterface;
use Faker\Factory as FakerFactory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadPostData extends Fixture implements ContainerAwareInterface, DependentFixtureInterface
{
    public const COUNT_POSTS = 50;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * {@inheritdoc}
     */
    public function setContainer(?ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManagerInterface $manager)
    {
        /* @var TextProcessor $textProcessor */
        $textProcessor = $this->container->get('mtt_blog.text_processor');

        $post = new Post();
        $post->setTitle('Тестовая запись')
            ->setUrl(RuTransform::ruTransform('Тестовая запись'))
            ->setCategory($manager->merge($this->getReference('category-2')))
            ->setDescription('метатег description тестовй записи')
            ->setRawText('<p>Тестовая запись, собственно...</p>')
            ->addTag($manager->merge($this->getReference('tag-test')));
        $textProcessor->processing($post);
        $manager->persist($post);
        $manager->flush();

        $post->setCommentsCount(2);

        $this->addReference('post-1', $post);

        $post2 = new Post();
        $post2->setTitle('запись про PHP')
            ->setUrl(RuTransform::ruTransform('запись про PHP'))
            ->setCategory($manager->merge($this->getReference('category-4')))
            ->setDescription('метатег description тестовой записи про ПХП')
            ->setRawText('<p>PHP (рекурсивный акроним словосочетания PHP: Hypertext Preprocessor) - это распространенный язык программирования общего назначения с открытым исходным кодом. PHP сконструирован специально для ведения Web-разработок и его код может внедряться непосредственно в HTML.</p>')
            ->addTag($manager->merge($this->getReference('tag-php')));
        $textProcessor->processing($post2);
        $manager->persist($post2);
        $manager->flush();

        $this->addReference('post-2', $post2);

        $post3 = new Post();
        $post3->setTitle('ещё о PHP')
            ->setUrl(RuTransform::ruTransform('ещё о PHP'))
            ->setCategory($manager->merge($this->getReference('category-4')))
            ->setDescription('description PHP')
            ->setRawText('<p>Ещё одна запись о PHP</p>')
            ->addTag($manager->merge($this->getReference('tag-php')));
        $textProcessor->processing($post3);
        $manager->persist($post3);
        $manager->flush();

        $this->addReference('post-3', $post3);

        $file = $manager->merge($this->getReference('file-1'));

        $post4 = new Post();
        $post4->setTitle('JavaScript, хоть и jQuery')
            ->setUrl(RuTransform::ruTransform('JavaScript, хоть и jQuery'))
            ->setCategory($manager->merge($this->getReference('category-5')))
            ->setDescription('description-JavaScript')
            ->setRawText('<p>JavaScript - прототипно-ориентированный сценарный язык программирования. Является диалектом языка ECMAScript</p><p>!'
                . $file->getId() . '!</p><!-- cut --><p>Параграф под катом</p>')
            ->addTag($manager->merge($this->getReference('tag-javascript')));
        $textProcessor->processing($post4);
        $manager->persist($post4);
        $manager->flush();

        $this->addReference('post-4', $post4);

        $file->setPost($post4);
        $manager->flush();

        $faker = FakerFactory::create('ru_RU');
        $faker->seed(303975);

        for ($i = 0; $i < self::COUNT_POSTS; $i++) {
            $title = sprintf('%s %s %s', $faker->word, $faker->word, $faker->word);

            $post = new Post();
            $post
                ->setTitle($title)
                ->setUrl(RuTransform::ruTransform($title))
                ->setCategory($manager->merge($this->getReference('category-' . $faker->numberBetween(1, 9))))
                ->setDescription($title)
                ->setRawText($faker->text($faker->numberBetween(100, 300)))
            ;
            $textProcessor->processing($post);

            for ($j = 0; $j < $faker->numberBetween(0, 7); $j++) {
                $post->addTag(
                    $manager->merge(
                        $this->getReference('tag-' . $faker->numberBetween(1, LoadTagData::COUNT_TAGS - 1))
                    )
                );
            }

            $manager->persist($post);
            $manager->flush();

            $this->addReference('post-' . (5 + $i), $post);
        }
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            LoadCategoryData::class,
            LoadMediaFileData::class,
            LoadTagData::class,
        ];
    }
}
