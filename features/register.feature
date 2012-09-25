Feature: register
    In order to use all the website's features
    As a guest
    I need to be able to register a new account

    Background:
        Given there is no user with username "username" in database

    Scenario: Register a new valid account
        Given I am on "user/register"
        And I should not see "error"
        And I should not see a "#kohana_error" element
        When I fill in the following:
            | username | username         |
            | password | password         |
            | email    | email@domain.com |
        And I press "Register"
        Then I should see "Dashboard"
        And I should not see a "#kohana_error" element

    Scenario: Existing users should not see the register page
        Given I am logged in as "username"
        When I go to "user/register"
        Then I should see "Dashboard"
        And I should not see a "#kohana_error" element

    Scenario: Registration error with invalid credentials
        Given I am on "user/register"
        When I fill in "username" with "x"
        And I press "Register"
        Then I should see "Register"
        And I should see "Your username should be more than 4 characters."
        And I should see "Your password is required."
        And I should see "Your email is required."
        And I should not see a "#kohana_error" element
