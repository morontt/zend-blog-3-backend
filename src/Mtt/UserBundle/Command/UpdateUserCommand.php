<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 14.06.15
 * Time: 18:39
 */

namespace Mtt\UserBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Mtt\UserBundle\Entity\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

class UpdateUserCommand extends Command
{
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @var EncoderFactory
     */
    private $encoderFactory;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param UserRepository $repository
     * @param EncoderFactory $encoderFactory
     * @param EntityManagerInterface $em
     */
    public function __construct(
        UserRepository $repository,
        EncoderFactory $encoderFactory,
        EntityManagerInterface $em
    ) {
        $this->repository = $repository;
        $this->encoderFactory = $encoderFactory;
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
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
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');

        $user = $this->repository->findOneByUsername($username);

        if (!$user) {
            $output->writeln('');
            $output->writeln(sprintf('<error>Error: user "%s" not found</error>', $username));
            $output->writeln('');
        } else {
            $encoder = $this->encoderFactory->getEncoder($user);

            $salt = bin2hex(random_bytes(16));
            $passwordHash = $encoder->encodePassword($password, $salt);
            $user
                ->setSalt($salt)
                ->setPassword($passwordHash);

            $this->em->flush();

            $output->writeln('');
            $output->writeln(sprintf('<info>Update user: <comment>%s</comment></info>', $username));
            $output->writeln('');
        }
    }
}
