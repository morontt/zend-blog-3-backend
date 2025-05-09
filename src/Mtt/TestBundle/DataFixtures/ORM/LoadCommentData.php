<?php

namespace Mtt\TestBundle\DataFixtures\ORM;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\DBALException;
use Doctrine\Persistence\ObjectManager as ObjectManagerInterface;
use Faker\Factory as FakerFactory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadCommentData extends Fixture implements DependentFixtureInterface, ContainerAwareInterface
{
    public const COUNT_COMMENTS = 450;

    /**
     * @var array
     */
    protected $commentPostRelation = [];

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(?ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     *
     * @throws DBALException
     */
    public function load(ObjectManagerInterface $manager)
    {
        $faker = FakerFactory::create('ru_RU');
        $faker->seed(618230);

        $repository = $manager->getRepository(Comment::class);
        for ($i = 0; $i < self::COUNT_COMMENTS; $i++) {
            $comment = new Comment();

            $text = $faker->realText($faker->numberBetween(30, 200));
            $text = iconv('UTF-8', 'UTF-8//IGNORE', $text);

            $comment
                ->setText($text)
                ->setIpAddress($faker->ipv4)
            ;

            if ($faker->numberBetween(0, 100) < 25) {
                $comment->setUser(
                    $manager->merge(
                        $this->getReference('user-' . $faker->numberBetween(1, LoadUserData::COUNT_USERS))
                    )
                );
            } else {
                $comment->setCommentator(
                    $manager->merge(
                        $this->getReference(
                            'commentator-' . $faker->numberBetween(1, 1 + LoadCommentatorData::COUNT_COMMENTATORS)
                        )
                    )
                );
            }

            $commentKey = 'comment-' . (string)($i + 1);
            if ($i > 20 && $faker->numberBetween(0, 100) < 25) {
                $parentCommentKey = 'comment-' . $faker->numberBetween(1, $i);
                $parent = $this->getReference($parentCommentKey);
                $manager->refresh($parent);
                $comment->setParent($manager->merge($parent));

                $postKey = $this->commentPostRelation[$parentCommentKey];
            } else {
                $postKey = 'post-' . $faker->numberBetween(1, 4 + LoadPostData::COUNT_POSTS);
            }

            $this->commentPostRelation[$commentKey] = $postKey;
            $comment->setPost($manager->merge($this->getReference($postKey)));

            $repository->save($comment);

            $this->addReference($commentKey, $comment);
        }

        $comment = new Comment();
        $comment->setText('Тестовый комментарий')
            ->setIpAddress('94.231.112.91')
            ->setPost($manager->merge($this->getReference('post-1')))
            ->setCommentator($manager->merge($this->getReference('commentator-1')));

        $repository->save($comment);

        $comment2 = new Comment();
        $comment2->setText('Ответ на тестовый комментарий')
            ->setIpAddress('62.72.188.111')
            ->setPost($manager->merge($this->getReference('post-1')))
            ->setUser($manager->merge($this->getReference('admin-user')))
            ->setParent($comment);

        $repository->save($comment2);

        /* @var \Doctrine\ORM\EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        $conn = $em->getConnection();

        $stmt = $conn->prepare('CALL update_all_comments_count()');
        $stmt->executeQuery();
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            LoadCommentatorData::class,
            LoadPostData::class,
            LoadUserData::class,
        ];
    }
}
