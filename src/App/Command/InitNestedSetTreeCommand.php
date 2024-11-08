<?php

namespace App\Command;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitNestedSetTreeCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
        $this->em->getConfiguration()->setSQLLogger(null);
    }

    protected function configure()
    {
        $this
            ->setName('mtt:tree:init')
            ->setDescription('Init nested-set tree for category and comments')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $startTime = microtime(true);

        $this->handleCategory();

        $output->writeln('');
        $output->writeln('<info>Update category tree</info>');

        $this->handlePosts($output);

        $endTime = microtime(true);

        $output->writeln('');
        $output->writeln(
            sprintf('<info>Total time: <comment>%s</comment> sec</info>', round($endTime - $startTime, 3))
        );

        return 0;
    }

    private function handleCategory(): void
    {
        $handled = [];

        /* @var \App\Repository\CategoryRepository $categoryRepo */
        $categoryRepo = $this->em->getRepository(Category::class);

        $qb = $categoryRepo->createQueryBuilder('c');
        $qb->update()
            ->set('c.nestedSet.leftKey', ':null')
            ->set('c.nestedSet.rightKey', ':null')
            ->set('c.nestedSet.depth', 1)
            ->setParameter('null', null)
            ->getQuery()
            ->execute()
        ;

        $qb = $categoryRepo->createQueryBuilder('c');
        $qb->orderBy('c.name');

        /* @var Category[] $categories */
        $categories = $qb->getQuery()->getResult();
        $idx = 0;
        foreach ($categories as $category) {
            if (!$category->getParent()) {
                $ns = $category->getNestedSet();
                $ns
                    ->setLeftKey(++$idx)
                    ->setRightKey(++$idx)
                ;

                $handled[] = $category->getId();
            }
        }

        $this->em->flush();

        do {
            $updateTree = false;
            foreach ($categories as $category) {
                if (!in_array($category->getId(), $handled)
                    && $parent = $category->getParent()
                ) {
                    if (in_array($parent->getId(), $handled)) {
                        $this->em->refresh($parent);
                        $nsParent = $parent->getNestedSet();

                        $categoryRepo->addToTree($category, $nsParent->getRightKey(), $nsParent->getDepth() + 1);

                        $handled[] = $category->getId();
                        $updateTree = true;

                        break;
                    }
                }
            }
        } while ($updateTree);
    }

    /**
     * @param OutputInterface $output
     */
    private function handlePosts(OutputInterface $output): void
    {
        /* @var \App\Repository\CommentRepository $commentsRepo */
        $commentsRepo = $this->em->getRepository(Comment::class);

        $qb = $commentsRepo->createQueryBuilder('c');
        $qb->update()
            ->set('c.nestedSet.leftKey', ':null')
            ->set('c.nestedSet.rightKey', ':null')
            ->set('c.nestedSet.depth', 1)
            ->setParameter('null', null)
            ->getQuery()
            ->execute()
        ;

        $postRepo = $this->em->getRepository(Post::class);
        $posts = $postRepo
            ->createQueryBuilder('p')
            ->select('p.id', 'p.url')
            ->innerJoin('p.comments', 'c')
            ->groupBy('p.id')
            ->getQuery()
            ->getArrayResult()
        ;

        foreach ($posts as $post) {
            $postId = $post['id'];
            $handled = [];
            $qb = $commentsRepo->createQueryBuilder('c');
            $qb
                ->where($qb->expr()->eq('c.post', ':postId'))
                ->setParameter('postId', $postId)
                ->orderBy('c.id');

            /* @var Comment[] $comments */
            $comments = $qb->getQuery()->getResult();
            $idx = 0;
            foreach ($comments as $comment) {
                if (!$comment->getParent()) {
                    $ns = $comment->getNestedSet();
                    $ns
                        ->setLeftKey(++$idx)
                        ->setRightKey(++$idx);

                    $handled[] = $comment->getId();
                }
            }

            $this->em->flush();

            do {
                $updateTree = false;
                foreach ($comments as $comment) {
                    if (!in_array($comment->getId(), $handled)
                        && $parent = $comment->getParent()
                    ) {
                        if (in_array($parent->getId(), $handled)) {
                            $this->em->refresh($parent);
                            $nsParent = $parent->getNestedSet();

                            $commentsRepo->addToTree(
                                $comment,
                                $nsParent->getRightKey(),
                                $nsParent->getDepth() + 1,
                                $postId
                            );

                            $handled[] = $comment->getId();
                            $updateTree = true;

                            break;
                        }
                    }
                }
            } while ($updateTree);

            $cnt = count($comments);
            $output->writeln(
                "<info>Update <comment>{$cnt}</comment> comments for topic: <comment>{$post['url']}</comment></info>"
            );
        }
    }
}
