Feature: comment
  Add comments

  @javascript
  Scenario: Add comment
    Given I am on "/"
      And I follow "Тестовая запись"
     Then I should see "Адрес электронной почты нигде не отображается"
      And I should see "2 комментария"
     When I fill in "Имя:" with "testname"
      And I fill in "E-mail:" with "test@example.org"
      And I fill in "Website:" with "http://example.org"
      And I fill in "Текст комментария:" with "TEST MESSAGE"
      And I press "Добавить комментарий"
      And pause "1000"
     Then I should see "TEST MESSAGE" in the "div#all-comments" element
      And I should see "3 комментария"
      And I should not see "[94.231.112.91]"

  Scenario: admin comment
    Given I am logged in as Admin
     When I am on "/article/testovaya-zapis"
     Then I should see "[94.231.112.91]"
      And restore database
      And clear cache