<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 17.06.15
 * Time: 1:23
 */

namespace Mtt\BlogBundle\Command;

use Dropbox;
use Mtt\BlogBundle\Entity\SystemParameters;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class DropboxAuthCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mtt:dropbox:auth')
            ->setDescription('Dropbox command-line authorization');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $key = $this->getContainer()->getParameter('dropbox_key');
        $secret = $this->getContainer()->getParameter('dropbox_secret');

        try {
            $appInfo = Dropbox\AppInfo::loadFromJson([
                'key' => $key,
                'secret' => $secret,
            ]);
        } catch (Dropbox\AppInfoLoadException $ex) {
            $output->writeln('<error>Error: %s</error>', $ex->getMessage());
            exit(1);
        }

        $webAuth = new Dropbox\WebAuthNoRedirect($appInfo, "examples-authorize", "en");
        $authorizeUrl = $webAuth->start();

        $output->writeln(sprintf("\n1. Go to: %s", $authorizeUrl));
        $output->writeln("2. Click <comment>\"Allow\"</comment> (you might have to log in first).");
        $output->writeln("3. Copy the authorization code.\n");

        $dialog = $this->getHelper('question');
        $question = new Question('Enter the authorization code here: ');
        $question->setValidator(function ($answer) {
            if (!trim($answer)) {
                throw new \RuntimeException(
                    'Empty code :('
                );
            }
            return $answer;
        });
        $question->setMaxAttempts(3);

        $authCode = trim($dialog->ask($input, $output, $question));

        list($accessToken, $userId) = $webAuth->finish($authCode);

        $output->writeln("\nAuthorization complete.");
        $output->writeln(sprintf("User ID: <comment>%s</comment>", $userId));
        $output->writeln(sprintf("Access Token: <comment>%s</comment>", $accessToken));

        $this->saveAccessToken($userId, $accessToken);
    }

    /**
     * @param $userId
     * @param $accessToken
     */
    protected function saveAccessToken($userId, $accessToken)
    {
        $em = $this->getContainer()
            ->get('doctrine')
            ->getManager();

        $sp = $em->getRepository('MttBlogBundle:SystemParameters')
            ->findOneByOptionKey(SystemParameters::DROPBOX_TOKEN);

        if (!$sp) {
            $sp = new SystemParameters();
            $sp->setOptionKey(SystemParameters::DROPBOX_TOKEN);
            $em->persist($sp);
        }

        $sp->setValue(serialize([
            'user_id' => $userId,
            'access_token' => $accessToken,
        ]));

        $em->flush();
    }
}
