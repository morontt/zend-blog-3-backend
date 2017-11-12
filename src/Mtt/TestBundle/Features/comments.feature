Feature: comments
  Check comments

  Scenario: show comment
    When I logged in as admin
    And I am on "/preview/testovaya-zapis"
    Then I should see "11 comments"

    When I am on "/api/comments"
    Then the response status code should be 200
    And the json path "[comments][0][text]" should contain "Ответ на тестовый комментарий"

    When I send "POST" to "/api/comments" with data:
      | comment[text]   | To be, or not to be |
      | comment[parent] | 1201                |
    Then the response status code should be 201
    And "commentator@example.org" should receive email with the text "Кто-то ответил на ваш комментарий"

    When I am on "/preview/testovaya-zapis"
    Then I should see "12 comments"
    And I should see "To be, or not to be" in the ".comments" element
