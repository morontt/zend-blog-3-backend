<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 14.06.15
 * Time: 18:39
 */

namespace App\Command\User;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteUserCommand extends Command
{
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param UserRepository $repository
     * @param EntityManagerInterface $em
     */
    public function __construct(UserRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('mtt:user:delete')
            ->setDescription('Delete user by username')
            ->addArgument('username', InputArgument::REQUIRED, 'username');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');

        $user = $this->repository->findOneByUsername($username);

        if (!$user) {
            $output->writeln('');
            $output->writeln(sprintf('<error>Error: user "%s" not found</error>', $username));
            $output->writeln('');
        } else {
            $this->em->remove($user);
            $this->em->flush();

            $output->writeln('');
            $output->writeln(sprintf('<info>Delete user: <comment>%s</comment></info>', $username));
            $output->writeln('');
        }
    }
}
