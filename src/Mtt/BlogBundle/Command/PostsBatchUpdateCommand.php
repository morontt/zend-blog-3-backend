<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 04.05.16
 * Time: 23:01
 */

namespace Mtt\BlogBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PostsBatchUpdateCommand extends ContainerAwareCommand
{
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
        $cnt = 0;

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $em->getConfiguration()->setSQLLogger(null);

        $repo = $em->getRepository('MttBlogBundle:Post');
        $textProcessor = $this->getContainer()->get('mtt_blog.text_processor');

        $i = 0;
        do {
            $updated = false;
            $posts = $repo->getPostsByIteration($i);

            foreach ($posts as $post) {
                $cnt++;
                $textProcessor->processing($post);
                $em->flush();
            }

            if (count($posts)) {
                $updated = true;
            }

            $i++;
        } while ($updated);

        $output->writeln('');
        $output->writeln(sprintf('<info>Update <comment>%d</comment> topics</info>', $cnt));

        $endTime = microtime(true);

        $output->writeln(
            sprintf('<info>Total time: <comment>%s</comment> sec</info>', round($endTime - $startTime, 3))
        );
    }
}
