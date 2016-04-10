Feature: preview
  Check topic preview

  Scenario: preview for anonimous
    Given I am on "/preview/javascript-khot-i-jquery"
    Then should be on "/login"

  Scenario: preview for admin
    When I logged in as admin
    And I am on "/preview/javascript-khot-i-jquery"
    Then I should see "Является диалектом языка ECMAScript"
    And the response should contain "gravatar.png"
    And the response should contain "file for testing"
