Feature: api
  Test API for ember.js application

  Scenario: tags
    When I logged in as admin
    When I am on "/api/tags"
    Then the response status code should be 200
    And the json path "[tags][0][name]" should contain "accusamus"
