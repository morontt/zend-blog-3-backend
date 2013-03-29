<?php

namespace Mtt\BlogBundle\Features\Context;

use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Behat\MinkExtension\Context\MinkContext;

use Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException,
    Behat\Behat\Context\Step;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Feature context.
 */
class FeatureContext extends MinkContext //if you want to test web
                  implements KernelAwareInterface
{
    private $kernel;
    private $parameters;

    /**
     * Initializes context with parameters from behat.yml.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Sets HttpKernel instance.
     * This method will be automatically called by Symfony2Extension ContextInitializer.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Given /^I am logged in as Admin$/
     */
    public function iAmLoggedInAsAdmin()
    {
        return array(
            new Step\Given('I am on "/login"'),
            new Step\When('fill in "username" with "admin"'),
            new Step\When('fill in "password" with "admin"'),
            new Step\When('I press "submit"'),
        );
    }

    /**
     * @Given /^restore database$/
     */
    public function restoreDatabase()
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $arguments = array(
            'command' => 'mtt:database:prepare',
            '--env' => 'test',
            '--no-output' => true,
        );

        $application->run(new ArrayInput($arguments));
    }

    /**
     * @Given /^clear cache$/
     */
    public function clearCache()
    {
        $directory = $this->kernel->getContainer()
            ->getParameter('cache_directory');

        if (is_dir($directory)) {
            $handle = opendir($directory);
            if ($handle) {

                while (false !== ($entry = readdir($handle))) {
                    if (!in_array($entry, array('.', '..'))) {
                        unlink($directory . DIRECTORY_SEPARATOR . $entry);
                    }
                }

                closedir($handle);
            }
        } else {
            throw new \Exception('fail cache directory');
        }
    }

    /**
     * @Given /^pause "([^"]*)"$/
     */
    public function pause($pause)
    {
        $this->getMink()->getSession()->wait($pause);
    }

    /**
     * @When /^I click xpath "([^"]*)"$/
     */
    public function iClickXpath($xpath)
    {
        $this->getMink()->getSession()->getDriver()->click($xpath);
    }

//
// Place your definition and hook methods here:
//
//    /**
//     * @Given /^I have done something with "([^"]*)"$/
//     */
//    public function iHaveDoneSomethingWith($argument)
//    {
//        $container = $this->kernel->getContainer();
//        $container->get('some_service')->doSomethingWith($argument);
//    }
//
}
