<?php

namespace Mtt\TestBundle\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context
{
    /**
     * @var Profiler
     */
    protected $profiler;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     *
     * @param Profiler $profiler
     */
    public function __construct(Profiler $profiler)
    {
        $this->profiler = $profiler;
    }

    /**
     * @When I logged in as admin
     */
    public function iLoggedInAsAdmin()
    {
        $this->visit('/login');
        $this->fillField('_username', 'admin');
        $this->fillField('_password', 'test');
        $this->pressButton('login');
    }

    /**
     * @Then the json path :path should contain :string
     *
     * @param $path
     * @param $string
     */
    public function theJsonPathShouldContain($path, $string)
    {
        $json = $this->getClientJSON();
        $accessor = PropertyAccess::createPropertyAccessor();

        $this->assert(
            $accessor->getValue($json, $path) === $string,
            sprintf('the json path "%s" should contain "%s"', $path, $string)
        );
    }

    /**
     * @When I send :method to :url with data:
     *
     * @param $method
     * @param $url
     * @param TableNode $table
     */
    public function iSendToWithData($method, $url, TableNode $table)
    {
        $baseUrl = rtrim($this->getMinkParameter('base_url'), '/');
        $client = $this->getClient();

        $client->request($method, $baseUrl . $url, $table->getRowsHash());
    }

    /**
     * @Then :recipient should receive email with the text :text
     *
     * @param string $recipient
     * @param string $text
     *
     * @throws \Exception
     */
    public function shouldReceiveEmailWithTheText($recipient, $text)
    {
        /* @var \Behat\Mink\Driver\Goutte\Client $client */
        $client = $this->getClient();

        $response = $client->getResponse();
        $token = $response->getHeader('X-Debug-Token');
        if (!$token) {
            throw new \Exception('X-Debug-Token not available');
        }

        $profile = $this->profiler->loadProfile($token);
        if (!$profile) {
            throw new \Exception('Symfony profile not available');
        }

        /* @var \Symfony\Bundle\SwiftmailerBundle\DataCollector\MessageDataCollector $mailCollector */
        $mailCollector = $profile->getCollector('swiftmailer');

        $collectedMessages = $mailCollector->getMessages();
        /* @var \Swift_Message $message */
        $message = $collectedMessages[0];

        $this->assert($message instanceof \Swift_Message, 'Message not instanceof Swift_Message');
        $this->assert(key($message->getTo()) === $recipient, 'The recipient is not ' . $recipient);
        $this->assert(mb_strpos($message->getBody(), $text) !== false, 'The message not contains text: ' . $text);
    }

    /**
     * @AfterStep
     *
     * @param AfterStepScope $event
     */
    public function printLastResponseOnError(AfterStepScope $event)
    {
        if (!$event->getTestResult()->isPassed() && getenv('CIRCLE_ARTIFACTS')) {
            file_put_contents(
                getenv('CIRCLE_ARTIFACTS') . '/' . uniqid() . '.html',
                $this->getSession()->getDriver()->getContent()
            );
        }
    }

    /**
     * @return array
     */
    protected function getClientJSON(): array
    {
        return json_decode($this->getSession()->getPage()->getContent(), true);
    }

    /**
     * @return \Behat\Mink\Driver\Goutte\Client
     */
    protected function getClient()
    {
        /* @var \Behat\Mink\Driver\Goutte\Client $client */
        $client = $this
            ->getSession()
            ->getDriver()
            ->getClient();

        return $client;
    }

    /**
     * @param bool $condition
     * @param string $message
     *
     * @throws \Exception
     */
    protected function assert($condition, $message)
    {
        if (!$condition) {
            throw new \Exception($message);
        }
    }
}
