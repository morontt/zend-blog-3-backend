Feature: comment
  Add comments

  @javascript
  Scenario: Add comment
    Given I am on "/"
      And I follow "Тестовая запись"
     Then I should see "Адрес электронной почты нигде не отображается"
     When I fill in "Имя:" with "testname"
      And I fill in "E-mail:" with "test@example.org"
      And I fill in "Website:" with "http://example.org"
      And I fill in "Текст комментария:" with "TEST MESSAGE"
      And I press "Добавить комментарий"
     Then I should see "TEST MESSAGE" in the "div#all-comments" element