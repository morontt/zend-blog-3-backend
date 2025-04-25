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
    private UserRepository $repository;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

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

    protected function configure(): void
    {
        $this
            ->setName('mtt:user:delete')
            ->setDescription('Delete user by username')
            ->addArgument('username', InputArgument::REQUIRED, 'username');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getArgument('username');

        $user = $this->repository->findOneByUsername($username);

        $output->writeln('');
        if (!$user) {
            $output->writeln(sprintf('<error>Error: user "%s" not found</error>', $username));
        } else {
            $this->em->remove($user);
            $this->em->flush();

            $output->writeln(sprintf('<info>Delete user: <comment>%s</comment></info>', $username));
        }
        $output->writeln('');

        return 0;
    }
}
