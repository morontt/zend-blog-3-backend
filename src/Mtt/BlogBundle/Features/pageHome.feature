Feature: pageHome
  Start page opening

  Scenario: Successfully opening start page
    Given I am on "/"
     Then I should see "Zend-blog-2"

  Scenario: Add comment
    Given I am on "/"
      And I follow "Установка Sahi"
     Then I should see "Адрес электронной почты нигде не отображается"
     When I fill in "Имя:" with "testname"
      And I fill in "E-mail:" with "test@example.org"
      And I fill in "Website:" with "http://example.org"
      And I fill in "Текст комментария:" with "TEST MESSAGE"
      And I press "Добавить комментарий"
     Then I should see "TEST MESSAGE" in the "div#all-comments" element