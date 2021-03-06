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
use Mtt\BlogBundle\Service\TextProcessor;
use Symfony\Component\Console\Command\Command;
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

    /**
     * @param EntityManagerInterface $em
     * @param TextProcessor $textProcessor
     */
    public function __construct(EntityManagerInterface $em, TextProcessor $textProcessor)
    {
        parent::__construct();

        $this->em = $em;
        $this->textProcessor = $textProcessor;

        $em->getConfiguration()->setSQLLogger(null);
    }

    protected function configure()
    {
        $this
            ->setName('mtt:posts:update')
            ->setDescription('Batch update all posts')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startTime = microtime(true);

        $repo = $this->em->getRepository('MttBlogBundle:Post');
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

        $cnt = 0;
        foreach ($postGenerator() as $posts) {
            foreach ($posts as $post) {
                $cnt++;
                $this->textProcessor->processing($post);
                $this->em->flush();
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
