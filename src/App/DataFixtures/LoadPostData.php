<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\MediaFile;
use App\Entity\Post;
use App\Entity\Tag;
use App\Service\TextProcessor;
use App\Utils\RuTransform;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;

class LoadPostData extends Fixture implements DependentFixtureInterface
{
    public const COUNT_POSTS = 32;

    public function __construct(
        private TextProcessor $textProcessor,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $post = new Post();
        $post->setTitle('Тестовая запись')
            ->setUrl(RuTransform::ruTransform('Тестовая запись'))
            ->setCategory($this->getReference('category-2', Category::class))
            ->setDescription('метатег description тестовй записи')
            ->setRawText('<p>Тестовая запись, собственно...</p>')
            ->addTag($this->getReference('tag-test', Tag::class));
        $this->textProcessor->processing($post);
        $manager->persist($post);
        $manager->flush();

        $post->setCommentsCount(2);

        $this->addReference('post-1', $post);

        $post2 = new Post();
        $post2->setTitle('запись про PHP')
            ->setUrl(RuTransform::ruTransform('запись про PHP'))
            ->setCategory($this->getReference('category-4', Category::class))
            ->setDescription('метатег description тестовой записи про ПХП')
            ->setRawText('<p>PHP (рекурсивный акроним словосочетания PHP: Hypertext Preprocessor) - это распространенный язык программирования общего назначения с открытым исходным кодом. PHP сконструирован специально для ведения Web-разработок и его код может внедряться непосредственно в HTML.</p>')
            ->addTag($this->getReference('tag-php', Tag::class));
        $this->textProcessor->processing($post2);
        $manager->persist($post2);
        $manager->flush();

        $this->addReference('post-2', $post2);

        $post3 = new Post();
        $post3->setTitle('ещё о PHP')
            ->setUrl(RuTransform::ruTransform('ещё о PHP'))
            ->setCategory($this->getReference('category-4', Category::class))
            ->setDescription('description PHP')
            ->setRawText('<p>Ещё одна запись о PHP</p>')
            ->addTag($this->getReference('tag-php', Tag::class));
        $this->textProcessor->processing($post3);
        $manager->persist($post3);
        $manager->flush();

        $this->addReference('post-3', $post3);

        $file = $this->getReference('file-1', MediaFile::class);

        $post4 = new Post();
        $post4->setTitle('JavaScript, хоть и jQuery')
            ->setUrl(RuTransform::ruTransform('JavaScript, хоть и jQuery'))
            ->setCategory($this->getReference('category-5', Category::class))
            ->setDescription('description-JavaScript')
            ->setRawText('<p>JavaScript - прототипно-ориентированный сценарный язык программирования. Является диалектом языка ECMAScript</p><p>!'
                . $file->getId() . '!</p><!-- cut --><p>Параграф под катом</p>')
            ->addTag($this->getReference('tag-javascript', Tag::class));
        $this->textProcessor->processing($post4);
        $manager->persist($post4);
        $manager->flush();

        $this->addReference('post-4', $post4);

        $file->setPost($post4);
        $manager->flush();

        $faker = FakerFactory::create('ru_RU');
        for ($i = 0; $i < self::COUNT_POSTS; $i++) {
            $faker->seed(303975 + $i);
            $title = sprintf('%s %s %s', $faker->word, $faker->word, $faker->word);

            $rawText = '';
            for ($j = 0; $j < $faker->numberBetween(1, 3); $j++) {
                $rawText .= '<p>' . $faker->text($faker->numberBetween(100, 200)) . "</p>\n";
            }

            $post = new Post();
            $post
                ->setTitle($title)
                ->setUrl(RuTransform::ruTransform($title))
                ->setCategory($this->getReference('category-' . $faker->numberBetween(1, 9), Category::class))
                ->setDescription($title)
                ->setRawText($rawText)
            ;
            $this->textProcessor->processing($post);

            for ($j = 0; $j < $faker->numberBetween(0, 4); $j++) {
                $post->addTag(
                    $this->getReference(
                        'tag-' . $faker->numberBetween(1, LoadTagData::COUNT_TAGS - 1),
                        Tag::class
                    )
                );
            }

            $manager->persist($post);
            $manager->flush();

            $this->addReference('post-' . (5 + $i), $post);
        }
    }

    public function getDependencies(): array
    {
        return [
            LoadCategoryData::class,
            LoadMediaFileData::class,
            LoadTagData::class,
        ];
    }
}
