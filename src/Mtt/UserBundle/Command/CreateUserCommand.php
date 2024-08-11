<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 14.06.15
 * Time: 18:39
 */

namespace Mtt\UserBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Mtt\UserBundle\Service\UserManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateUserCommand extends Command
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @param UserManager $userManager
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $em
     */
    public function __construct(
        UserManager $userManager,
        ValidatorInterface $validator,
        EntityManagerInterface $em
    ) {
        $this->userManager = $userManager;
        $this->validator = $validator;
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('mtt:user:create')
            ->setDescription('Create new user')
            ->addArgument('username', InputArgument::REQUIRED, 'username')
            ->addArgument('email', InputArgument::REQUIRED, 'email')
            ->addOption('password', 'p', InputOption::VALUE_OPTIONAL, 'password');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $email = $input->getArgument('email');
        $password = $input->getOption('password');

        $user = $this->userManager->createUser($username, $email, $password);

        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            $output->writeln('');
            foreach ($errors as $error) {
                $output->writeln(sprintf('<error>Error: %s</error>', $error->getMessage()));
            }
            $output->writeln('');
        } else {
            $this->em->persist($user);
            $this->em->flush();

            $output->writeln('');
            $output->writeln(sprintf('<info>Create user: <comment>%s</comment></info>', $username));
            $output->writeln('');
        }
    }
}
