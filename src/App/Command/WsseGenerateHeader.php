<?php

namespace App\Command;

use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WsseGenerateHeader extends Command
{
    /**
     * @var UserRepository
     */
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }

    protected function configure(): void
    {
        $this
            ->setName('mtt:wsse:generate-header')
            ->setDescription('Generate WSSE header by user')
            ->addArgument('username', InputArgument::REQUIRED, 'The username of the user.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getArgument('username');

        $user = $this->repository->findOneByUsername($username);
        if (!$user) {
            $output->writeln("<error>User \"{$username}\" not found</error>");

            return 1;
        }

        $output->writeln('<comment>Authorization:</comment> WSSE profile="UsernameToken"');

        try {
            $nonce = random_bytes(12);
        } catch (\Exception $e) {
            $nonce = openssl_random_pseudo_bytes(12, $isSourceStrong);
            if ($isSourceStrong === false || $nonce === false) {
                throw new \RuntimeException('IV generation failed');
            }
        }

        $created = date('c');
        $digest = base64_encode(
            sha1($nonce . $created . $user->getWsseKey(), true)
        );

        $output->writeln(
            sprintf(
                '<comment>X-WSSE:</comment> UsernameToken Username="%s",PasswordDigest="%s",Nonce="%s",Created="%s"',
                $username,
                $digest,
                base64_encode($nonce),
                $created
            )
        );

        return 0;
    }
}
