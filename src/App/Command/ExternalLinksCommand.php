<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 22.04.18
 * Time: 15:38
 */

namespace App\Command;

use App\Entity\Comment;
use App\Utils\ExternalLinkProcessor;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
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
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $em->getConfiguration()->setSQLLogger(null);

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('mtt:comments:no-follow')
            ->setDescription('Batch update all posts')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $startTime = microtime(true);
        $cnt = 0;

        $repo = $this->em->getRepository(Comment::class);
        $linkProcessor = new ExternalLinkProcessor(['xelbot.com']);

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

        return 0;
    }
}
