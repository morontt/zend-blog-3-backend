<?php

namespace Mtt\BlogBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Mtt\BlogBundle\Entity\Category;
use Mtt\BlogBundle\Entity\Comment;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitNestedSetTreeCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

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

    protected function execute(InputInterface $input, OutputInterface $output)
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
    }

    private function handleCategory()
    {
        $handled = [];

        /* @var \Mtt\BlogBundle\Entity\Repository\CategoryRepository $categoryRepo */
        $categoryRepo = $this->em->getRepository('MttBlogBundle:Category');

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
    private function handlePosts(OutputInterface $output)
    {
        /* @var \Mtt\BlogBundle\Entity\Repository\CommentRepository $commentsRepo */
        $commentsRepo = $this->em->getRepository('MttBlogBundle:Comment');

        $qb = $commentsRepo->createQueryBuilder('c');
        $qb->update()
            ->set('c.nestedSet.leftKey', ':null')
            ->set('c.nestedSet.rightKey', ':null')
            ->set('c.nestedSet.depth', 1)
            ->setParameter('null', null)
            ->getQuery()
            ->execute()
        ;

        $postRepo = $this->em->getRepository('MttBlogBundle:Post');
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
