Feature: addproject
    In order to organise my WIPs
    As a registered user
    I need to be able to add a new project

    Background:
        Given I am logged in as "username"

    Scenario: Add a new project
        Given I am on "project/add"
        And I should see "Add Project"
        And I should not see a "#kohana_error" element
        When I fill in the following:
            | name    | name    |
            | summary | summary |
        And I press "Add"
        Then I should be on "project/view"
        And I should not see a "#kohana_error" element

    Scenario: Add a new project with invalid data
        Given I am on "project/add"
        And I should see "Add Project"
        And I should not see a "#kohana_error" element
        When I press "Add"
        Then I should see "Add Project"
        And I should see "A project name is required."
        And I should see "A project summary is required."
        And I should not see a "#kohana_error" element

    Scenario: Visit the add project page whilst logged out
        Given I am logged out
        When I go to "project/add"
        Then I should see "Login"
        And I should not see a "#kohana_error" element
