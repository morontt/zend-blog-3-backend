<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 04.05.16
 * Time: 23:01
 */

namespace App\Command;

use App\Entity\Post;
use App\Model\Image;
use App\Service\PictureTagBuilder;
use App\Service\TextProcessor;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PostsBatchUpdateCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private TextProcessor $textProcessor,
        private PictureTagBuilder $ptb,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('mtt:posts:update')
            ->setDescription('Batch update all posts')
            ->addArgument('article-id', InputArgument::OPTIONAL, 'article ID')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws JsonException
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $startTime = microtime(true);

        $postId = $input->getArgument('article-id');
        /** @var \App\Repository\PostRepository */
        $repo = $this->em->getRepository(Post::class);
        if ($postId) {
            $postGenerator = function () use ($repo, $postId) {
                $post = $repo->find((int)$postId);
                if (!$post) {
                    return;
                }

                yield [$post];
            };
        } else {
            $postGenerator = function () use ($repo) {
                $i = 0;
                while (true) {
                    $posts = $repo->getPostsForIteration($i);

                    $i++;
                    if (!count($posts)) {
                        return;
                    }

                    yield $posts;
                }
            };
        }

        $cnt = 0;
        foreach ($postGenerator() as $posts) {
            /** @var Post $post */
            foreach ($posts as $post) {
                $cnt++;
                $this->textProcessor->processing($post);
                $this->em->flush();

                $media = $post->getDefaultImage();
                if ($media && $media->isImage()) {
                    $picture = $this->ptb->featuredPictureTag($media);
                    $media->setPictureTag($picture);

                    $srcSet = $this->ptb->getSrcSet(new Image($media));
                    $srcSetData = $srcSet->toArray();
                    if (!empty($srcSetData)) {
                        $media->setSrcSet(json_encode($srcSetData, JSON_THROW_ON_ERROR));
                    }

                    $this->em->flush();
                }
            }
        }

        $output->writeln('');
        $output->writeln(sprintf('<info>Update <comment>%d</comment> topics</info>', $cnt));

        $endTime = microtime(true);

        $output->writeln(
            sprintf('<info>Total time: <comment>%s</comment> sec</info>', round($endTime - $startTime, 3))
        );

        return 0;
    }
}
