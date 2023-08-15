<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 04.05.16
 * Time: 23:01
 */

namespace Mtt\BlogBundle\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Mtt\BlogBundle\Model\Image;
use Mtt\BlogBundle\Service\ImageManager;
use Mtt\BlogBundle\Service\TextProcessor;
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

    /**
     * @var TextProcessor
     */
    private $textProcessor;

    private ImageManager $im;

    /**
     * @param EntityManagerInterface $em
     * @param TextProcessor $textProcessor
     * @param ImageManager $im
     */
    public function __construct(EntityManagerInterface $em, TextProcessor $textProcessor, ImageManager $im)
    {
        parent::__construct();

        $this->em = $em;
        $this->im = $im;
        $this->textProcessor = $textProcessor;

        $em->getConfiguration()->setSQLLogger(null);
    }

    protected function configure()
    {
        $this
            ->setName('mtt:posts:update')
            ->setDescription('Batch update all posts')
            ->addArgument('articleId', InputArgument::OPTIONAL, 'article ID')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startTime = microtime(true);

        $postId = $input->getArgument('articleId');
        $repo = $this->em->getRepository('MttBlogBundle:Post');
        if ($postId) {
            $postGenerator = function () use ($repo, $postId) {
                $post = $repo->find((int)$postId);
                if (!$post) {
                    return;
                }

                yield [$post];

                return;
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
            /* @var \Mtt\BlogBundle\Entity\Post $post */
            foreach ($posts as $post) {
                $cnt++;
                $this->textProcessor->processing($post);
                $this->em->flush();

                $media = $post->getDefaultImage();
                if ($media && $media->isImage()) {
                    $picture = $this->im->featuredPictureTag($media);
                    $media->setPictureTag($picture);

                    $srcSet = (new Image($media))->getSrcSet();
                    $srcSetData = $srcSet->toArray();
                    if (!empty($srcSetData)) {
                        $media->setSrcSet(json_encode($srcSetData));
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
    }
}
