Feature: dashboard
    In order to get an overview of my WIPs
    As a registered user
    I need to be able to view my dashboard

    Background:
        Given I am logged in as "username"

    Scenario: Visit my dashboard
        Given I am on "user/dashboard"
        Then I should see "Dashboard"
        And I should see "username"
        And I should not see a "#kohana_error" element

    Scenario: Visit my dashboard whilst logged out
        Given I am logged out
        When I go to "user/dashboard"
        Then I should see "Login"
        And I should not see a "#kohana_error" element
