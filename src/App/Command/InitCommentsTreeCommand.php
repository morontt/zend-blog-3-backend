<?php

namespace App\Command;

use App\Entity\Comment;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'mtt:init-tree:comments',
    description: 'Init nested-set tree for comments',
)]
class InitCommentsTreeCommand extends Command
{
    public function __construct(private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('article-ids', null, InputOption::VALUE_REQUIRED, 'article IDs')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $startTime = microtime(true);

        $articleIds = $input->getOption('article-ids');
        $ids = [];
        if ($articleIds) {
            $ids = array_map(
                fn ($el) => (int)$el,
                explode(',', $articleIds)
            );
        }

        $this->handlePosts($output, $ids);

        $endTime = microtime(true);

        $output->writeln('');
        $output->writeln(
            sprintf('<info>Total time: <comment>%s</comment> sec</info>', round($endTime - $startTime, 3))
        );

        return 0;
    }

    /**
     * @param int[] $articleIds
     */
    private function handlePosts(OutputInterface $output, array $articleIds): void
    {
        /** @var \App\Repository\CommentRepository */
        $commentsRepo = $this->em->getRepository(Comment::class);

        $qb = $commentsRepo->createQueryBuilder('c');
        $qb->update()
            ->set('c.nestedSet.leftKey', 'NULL')
            ->set('c.nestedSet.rightKey', 'NULL')
            ->set('c.nestedSet.depth', 1)
        ;

        if ($articleIds) {
            $qb
                ->where($qb->expr()->in('c.post', ':ids'))
                ->setParameter('ids', $articleIds)
            ;
        }

        $qb->getQuery()->execute();

        /** @var \App\Repository\PostRepository */
        $postRepo = $this->em->getRepository(Post::class);
        $postsQb = $postRepo
            ->createQueryBuilder('p')
            ->select('p.id', 'p.url')
            ->innerJoin('p.comments', 'c')
            ->groupBy('p.id')
        ;

        if ($articleIds) {
            $postsQb
                ->where($postsQb->expr()->in('p.id', ':ids'))
                ->setParameter('ids', $articleIds)
            ;
        }

        $posts = $postsQb
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

            /** @var Comment[] $comments */
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
