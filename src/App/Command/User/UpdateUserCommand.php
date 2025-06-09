<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 14.06.15
 * Time: 18:39
 */

namespace App\Command\User;

use App\Repository\UserRepository;
use App\Service\UserManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateUserCommand extends Command
{
    /**
     * @var UserRepository
     */
    private UserRepository $repository;

    /**
     * @var UserManager
     */
    private UserManager $userManager;

    /**
     * @param UserRepository $repository
     * @param UserManager $userManager
     */
    public function __construct(
        UserRepository $repository,
        UserManager $userManager,
    ) {
        $this->repository = $repository;
        $this->userManager = $userManager;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('mtt:user:update')
            ->setDescription('Update user password by username')
            ->addArgument('username', InputArgument::REQUIRED, 'username')
            ->addArgument('password', InputArgument::REQUIRED, 'password')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @throws \App\Exception\ShortPasswordException
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');

        $user = $this->repository->findOneByUsername($username);

        $output->writeln('');
        if (!$user) {
            $output->writeln(sprintf('<error>Error: user "%s" not found</error>', $username));
        } else {
            $this->userManager->updatePassword($user, $password);

            $output->writeln(sprintf('<info>Update user: <comment>%s</comment></info>', $username));
        }
        $output->writeln('');

        return 0;
    }
}
