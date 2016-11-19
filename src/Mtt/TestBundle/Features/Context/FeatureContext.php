<?php

namespace Mtt\TestBundle\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
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
}
