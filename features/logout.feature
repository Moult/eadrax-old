Feature: logout
    In order to stop using my account
    As a logged in user
    I need to be able to logout

    Background:
        Given there is a user with username "username" in database
        And I am logged in as "username"

    Scenario: Logout succesfully
        Given I am on "user/dashboard"
        And I should see "Dashboard"
        When I follow "Logout"
        And I go to "user/dashboard"
        Then I should see "Login"
