<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Commentator;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;

class LoadCommentData extends Fixture implements DependentFixtureInterface
{
    public const COUNT_COMMENTS = 450;

    /** @var array<string, string> */
    protected $commentPostRelation = [];

    public function load(ObjectManager $manager): void
    {
        $faker = FakerFactory::create('ru_RU');
        $faker->seed(618230);

        /** @var \App\Repository\CommentRepository */
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
                    $this->getReference('user-' . $faker->numberBetween(1, LoadUserData::COUNT_USERS), User::class)
                );
            } else {
                $comment->setCommentator(
                    $this->getReference(
                        'commentator-' . $faker->numberBetween(1, 1 + LoadCommentatorData::COUNT_COMMENTATORS),
                        Commentator::class
                    )
                );
            }

            $commentKey = 'comment-' . (string)($i + 1);
            if ($i > 20 && $faker->numberBetween(0, 100) < 25) {
                $parentCommentKey = 'comment-' . $faker->numberBetween(1, $i);
                $parent = $this->getReference($parentCommentKey, Comment::class);
                $manager->refresh($parent);
                $comment->setParent($parent);

                $postKey = $this->commentPostRelation[$parentCommentKey];
            } else {
                $postKey = 'post-' . $faker->numberBetween(1, 4 + LoadPostData::COUNT_POSTS);
            }

            $this->commentPostRelation[$commentKey] = $postKey;
            $comment->setPost($this->getReference($postKey, Post::class));

            $repository->save($comment);

            $this->addReference($commentKey, $comment);
        }

        $comment = new Comment();
        $comment->setText('Тестовый комментарий')
            ->setIpAddress('94.231.112.91')
            ->setPost($this->getReference('post-1', Post::class))
            ->setCommentator($this->getReference('commentator-1', Commentator::class));

        $repository->save($comment);

        $comment2 = new Comment();
        $comment2->setText('Ответ на тестовый комментарий')
            ->setIpAddress('62.72.188.111')
            ->setPost($this->getReference('post-1', Post::class))
            ->setUser($this->getReference('admin-user', User::class))
            ->setParent($comment);

        $repository->save($comment2);

        if ($manager instanceof EntityManagerInterface) {
            $conn = $manager->getConnection();

            $stmt = $conn->prepare('CALL update_all_comments_count()');
            $stmt->executeQuery();
        }
    }

    public function getDependencies(): array
    {
        return [
            LoadCommentatorData::class,
            LoadPostData::class,
            LoadUserData::class,
        ];
    }
}
