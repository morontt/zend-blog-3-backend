<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentatorRepository;
use App\Utils\VerifyEmail;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FakeEmailCheck extends Command
{
    private CommentatorRepository $repository;

    private EntityManagerInterface $em;

    public function __construct(CommentatorRepository $repository, EntityManagerInterface $em)
    {
        parent::__construct();

        $this->repository = $repository;
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setName('mtt:fake-email:check')
            ->setDescription('Check commentators email')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rows = [];

        $commentators = $this->repository->getWithUncheckedEmails();
        foreach ($commentators as $entity) {
            $result = VerifyEmail::isValid($entity->getEmail());

            $entity
                ->setFakeEmail(!$result)
                ->setEmailCheck(new \DateTime())
            ;

            $rows[] = [
                $entity->getId(),
                $entity->getEmail(),
                $result ? '<fg=green>true</>' : '<fg=red>false</>',
            ];
        }

        $this->em->flush();

        if (count($rows)) {
            $table = new Table($output);
            $table->setHeaders(['ID', 'email', 'result']);

            $table->setRows($rows)->render();
        } else {
            $output->writeln('Nothing to check');
        }
    }
}
