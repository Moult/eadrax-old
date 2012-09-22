<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/../../spec/bootstrap.php';

/**
 * Features context.
 */
class FeatureContext extends Behat\MinkExtension\Context\MinkContext
{
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        // Initialize your context here
    }

    /**
     * @Given /^there is no user with username "([^"]*)" in database$/
     */
    public function thereIsNoUserWithUsernameInDatabase($username)
    {
        DB::delete('users')->where('username', '=', $username)->execute();
    }

    /**
     * @Given /^I am logged in$/
     */
    public function iAmLoggedIn()
    {
        $login_attempt = Auth::instance()->login('username', 'password');
        if ( ! $login_attempt)
        {
            throw new Exception('Could not log in user.');
        }
    }

    /**
     * @Given /^I am logged out$/
     */
    public function iAmLoggedOut()
    {
        Auth::instance()->logout();
    }

}
