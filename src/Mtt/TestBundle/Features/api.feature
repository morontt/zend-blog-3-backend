Feature: api
  Test API for ember.js application

  Scenario: tags
    When I logged in as admin
    When I am on "/api/tags"
    Then the response status code should be 200
    And the json path "[tags][0][name]" should contain "accusamus"

    When I send "POST" to "/api/tags" with data:
      | tag[name] | a 104 |
    Then the response status code should be 201

    When I am on "/api/tags"
    Then the json path "[tags][0][name]" should contain "a 104"
    And the json path "[tags][0][url]" should contain "a-104"

  Scenario: categories
    When I logged in as admin
    When I am on "/api/categories"
    Then the response status code should be 200
    And the json path "[categories][0][name]" should contain "Database"

    When I send "POST" to "/api/categories" with data:
      | category[name]     | SQL Server |
      | category[parentId] | 7          |
    Then the response status code should be 201

    When I am on "/api/categories"
    Then the json path "[categories][7][name]" should contain "SQL Server"
    And the json path "[categories][7][url]" should contain "sql-server"
