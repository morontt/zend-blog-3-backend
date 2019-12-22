<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 22.04.18
 * Time: 15:38
 */

namespace Mtt\BlogBundle\Command;

use Doctrine\ORM\EntityManager;
use Mtt\BlogBundle\Entity\Comment;
use Mtt\BlogBundle\Utils\ExternalLinkProcessor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExternalLinksCommand extends Command
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $em->getConfiguration()->setSQLLogger(null);

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('mtt:comments:no-follow')
            ->setDescription('Batch update all posts')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startTime = microtime(true);
        $cnt = 0;

        $repo = $this->em->getRepository('MttBlogBundle:Comment');
        $linkProcessor = new ExternalLinkProcessor(['morontt.info']);

        $i = 0;
        do {
            $updated = false;

            $qb = $repo->createQueryBuilder('c');
            $qb
                ->orderBy('c.id')
                ->setFirstResult($i * 20)
                ->setMaxResults(20)
            ;

            /* @var Comment[] $comments */
            $comments = $qb->getQuery()->getResult();
            $output->writeln(count($comments));
            foreach ($comments as $entity) {
                $newContent = $linkProcessor->upgradeLinks($entity->getText());
                if ($newContent) {
                    $cnt++;
                    $entity->setText($newContent);
                    $this->em->flush();
                }
            }

            if (count($comments)) {
                $updated = true;
            }

            $i++;
        } while ($updated);

        $output->writeln('');
        $output->writeln(sprintf('<info>Update <comment>%d</comment> comments</info>', $cnt));

        $endTime = microtime(true);

        $output->writeln(
            sprintf('<info>Total time: <comment>%s</comment> sec</info>', round($endTime - $startTime, 3))
        );
    }
}
