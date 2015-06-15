Feature: login
  Check security

  Scenario: login
    Given I am on homepage
    Then I should see "login"

    When I fill in "_username" with "admin"
    And I fill in "_password" with "test"
    And I press "login"
    Then I should see "Превед, admin"
