<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 14.06.15
 * Time: 18:39
 */
namespace Mtt\UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Mtt\UserBundle\Entity\User;

class CreateUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mtt:user:create')
            ->setDescription('Create new user')
            ->addArgument('username', InputArgument::REQUIRED, 'username')
            ->addArgument('email', InputArgument::REQUIRED, 'email')
            ->addOption('password', 'p', InputOption::VALUE_OPTIONAL, 'password', 'admin');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $email = $input->getArgument('email');
        $password = $input->getOption('password');

        $user = new User();
        $encoder = $this->getContainer()
            ->get('security.encoder_factory')
            ->getEncoder($user);

        $passwordHash = $encoder->encodePassword($password, $user->getSalt());
        $user
            ->setUsername($username)
            ->setMail($email)
            ->setPassword($passwordHash);

        $errors = $this->getContainer()
            ->get('validator')
            ->validate($user);

        if (count($errors) > 0) {
            $output->writeln('');
            foreach ($errors as $error) {
                $output->writeln(sprintf('<error>Error: %s</error>', $error->getMessage()));
            }
            $output->writeln('');
        } else {
            $em = $this->getContainer()
                ->get('doctrine')
                ->getManager();

            $em->persist($user);
            $em->flush();

            $output->writeln('');
            $output->writeln(sprintf('<info>Create user: <comment>%s</comment></info>', $username));
            $output->writeln('');
        }
    }
}
