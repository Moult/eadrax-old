Feature: register
    In order to use all the website's features
    As a guest
    I need to be able to register a new account

    Background:
        Given there is no user with username "username" in database

    Scenario: Register a new valid account
        Given I am on "user/register"
        When I fill in the following:
            | username | username         |
            | password | password         |
            | email    | email@domain.com |
        And I press "Register"
        Then I should see "Dashboard"
