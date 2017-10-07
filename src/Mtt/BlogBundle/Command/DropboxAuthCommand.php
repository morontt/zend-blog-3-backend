<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 17.06.15
 * Time: 1:23
 */

namespace Mtt\BlogBundle\Command;

use Mtt\BlogBundle\Entity\SystemParameters;
use Mtt\BlogBundle\OAuth2\DropboxProvider;
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

        $provider = new DropboxProvider([
            'clientId' => $key,
            'clientSecret' => $secret,
        ]);

        $authorizationUrl = $provider->getAuthorizationUrl();

        $output->writeln(sprintf("\n1. Go to: %s", $authorizationUrl));
        $output->writeln('2. Click <comment>"Allow"</comment> (you might have to log in first).');
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

        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $authCode,
        ]);

        $output->writeln("\nAuthorization complete.");
        $output->writeln(sprintf('Access Token: <comment>%s</comment>', $accessToken->getToken()));

        $this->saveAccessToken($accessToken->getToken());
    }

    /**
     * @param string $accessToken
     */
    protected function saveAccessToken(string $accessToken)
    {
        $storage = $this->getContainer()
            ->get('mtt_blog.sys_parameters_storage');

        $storage->saveParameter(SystemParameters::DROPBOX_TOKEN, $accessToken, true);
    }
}
