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
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PostsBatchUpdateCommand extends Command
{
    /**
     * @var EntityManager
     */
    private $em;

    private TextProcessor $textProcessor;

    private PictureTagBuilder $ptb;

    /**
     * @param EntityManagerInterface $em
     * @param TextProcessor $textProcessor
     * @param PictureTagBuilder $ptb
     */
    public function __construct(EntityManagerInterface $em, TextProcessor $textProcessor, PictureTagBuilder $ptb)
    {
        parent::__construct();

        $this->em = $em;
        $this->ptb = $ptb;
        $this->textProcessor = $textProcessor;

        $em->getConfiguration()->setSQLLogger(null);
    }

    protected function configure(): void
    {
        $this
            ->setName('mtt:posts:update')
            ->setDescription('Batch update all posts')
            ->addArgument('articleId', InputArgument::OPTIONAL, 'article ID')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws JsonException
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $startTime = microtime(true);

        $postId = $input->getArgument('articleId');
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
            /* @var Post $post */
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
