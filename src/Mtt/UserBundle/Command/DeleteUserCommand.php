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

class DeleteUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mtt:user:delete')
            ->setDescription('Delete user by username')
            ->addArgument('username', InputArgument::REQUIRED, 'username');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');

        $em = $this->getContainer()
            ->get('doctrine')
            ->getManager();

        $user = $em->getRepository('MttUserBundle:User')
            ->findOneByUsername($username);

        if (!$user) {
            $output->writeln('');
            $output->writeln(sprintf('<error>Error: user "%s" not found</error>', $username));
            $output->writeln('');
        } else {
            $em->remove($user);
            $em->flush();

            $output->writeln('');
            $output->writeln(sprintf('<info>Delete user: <comment>%s</comment></info>', $username));
            $output->writeln('');
        }
    }
}
