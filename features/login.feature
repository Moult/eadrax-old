Feature: login
    In order to use account based features
    As a guest with an existing account
    I need to be able to login with my account

    Background:
        Given there is a user with username "username" in database

    Scenario: Login succesfully
        Given I am on "user/login"
        And I should see "Login"
        When I fill in the following:
            | username | username |
            | password | username |
        And I press "Login"
        Then I should not see "Login"

    Scenario: Login with bad credentials
        Given there is no user with username "username" in database
        And I am on "user/login"
        And I should see "Login"
        When I fill in the following:
            | username | username |
            | password | username |
        And I press "Login"
        Then I should see "Login"
        And I should see "error"
        And I should see "No account with those user details exist"

    Scenario: Existing users should not see the login page
        Given I am logged in as "username"
        When I go to "user/login"
        Then I should see "Dashboard"
