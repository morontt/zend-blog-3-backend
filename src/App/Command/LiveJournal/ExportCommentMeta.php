<?php
/**
 * User: morontt
 * Date: 06.05.2025
 * Time: 16:20
 */

namespace App\Command\LiveJournal;

use App\Entity\LjCommentMeta;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportCommentMeta extends Command
{
    private EntityManagerInterface $em;
    private EntityRepository $commentMetaRepo;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
        $this->commentMetaRepo = $this->em->getRepository(LjCommentMeta::class);
    }

    protected function configure(): void
    {
        $this
            ->setName('mtt:lj:comment-meta')
            ->setDescription('Export comment meta-data')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $meta = simplexml_load_string(
            file_get_contents(APP_VAR_DIR . '/livejournal/comments-xml/comment_meta_9999.xml')
        );
        $cnt = 0;
        foreach ($meta->usermaps[0] as $item) {
            $posterId = (int)$item['id'];
            $metaObj = $this->commentMetaRepo->findOneBy(['posterId' => $posterId]);
            if ($metaObj) {
                continue;
            }

            $commentMeta = new LjCommentMeta();
            $this->em->persist($commentMeta);

            $commentMeta
                ->setPosterId($posterId)
                ->setLjName((string)$item['user'])
            ;

            $output->writeln(sprintf('id: %d, user: %s', $commentMeta->getPosterId(), $commentMeta->getLjName()));

            $cnt++;
            if ($cnt === 30) {
                $this->em->flush();
                $cnt = 0;
                $output->writeln('>>> Flush');
                $output->writeln('');
            }
        }
        $this->em->flush();
        $output->writeln('>>> Flush');

        return 0;
    }
}
