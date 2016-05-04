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
use Symfony\Component\Console\Output\OutputInterface;

class UpdateUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mtt:user:update')
            ->setDescription('Update user password by username')
            ->addArgument('username', InputArgument::REQUIRED, 'username')
            ->addArgument('password', InputArgument::REQUIRED, 'password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');

        $em = $this->getContainer()
            ->get('doctrine')
            ->getManager();

        /* @var \Mtt\UserBundle\Entity\User $user */
        $user = $em->getRepository('MttUserBundle:User')
            ->findOneByUsername($username);

        if (!$user) {
            $output->writeln('');
            $output->writeln(sprintf('<error>Error: user "%s" not found</error>', $username));
            $output->writeln('');
        } else {
            $salt = md5(uniqid(null, true));

            $encoder = $this->getContainer()
                ->get('security.encoder_factory')
                ->getEncoder($user);

            $passwordHash = $encoder->encodePassword($password, $salt);
            $user
                ->setSalt($salt)
                ->setPassword($passwordHash);

            $em->flush();

            $output->writeln('');
            $output->writeln(sprintf('<info>Update user: <comment>%s</comment></info>', $username));
            $output->writeln('');
        }
    }
}
