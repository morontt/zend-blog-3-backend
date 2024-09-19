<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 17.06.15
 * Time: 1:23
 */

namespace App\Command;

use Doctrine\ORM\ORMException;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use App\Entity\SystemParameters;
use App\OAuth2\Client\DropboxProvider;
use App\Service\SystemParametersStorage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class DropboxAuthCommand extends Command
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var SystemParametersStorage
     */
    private $storage;

    /**
     * @param SystemParametersStorage $storage
     * @param string $dropboxKey
     * @param string $dropboxSecret
     */
    public function __construct(SystemParametersStorage $storage, string $dropboxKey, string $dropboxSecret)
    {
        parent::__construct();

        $this->key = $dropboxKey;
        $this->secret = $dropboxSecret;
        $this->storage = $storage;
    }

    protected function configure()
    {
        $this
            ->setName('mtt:dropbox:auth')
            ->setDescription('Dropbox command-line authorization');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @throws ORMException
     * @throws IdentityProviderException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $provider = new DropboxProvider([
            'clientId' => $this->key,
            'clientSecret' => $this->secret,
        ]);

        $authorizationUrl = $provider->getAuthorizationUrl();

        $output->writeln(sprintf("\n1. Go to: %s", $authorizationUrl));
        $output->writeln('2. Click <comment>"Allow"</comment> (you might have to log in first).');
        $output->writeln("3. Copy the authorization code.\n");

        $dialog = $this->getHelper('question');
        $question = new Question('Enter the authorization code here: ');
        $question->setValidator(function ($answer) {
            if (!trim($answer)) {
                throw new \RuntimeException('Empty code :(');
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
     *
     * @throws ORMException
     */
    protected function saveAccessToken(string $accessToken)
    {
        $this->storage->saveParameter(SystemParameters::DROPBOX_TOKEN, $accessToken, true);
    }
}
