Feature: pageHome
  Start page opening

  Scenario: Просмотр стартовой страницы
    Given I am on "/"
     Then I should see "Test-Blog-2"
      And I should see "Читать далее"
      And I should see "Тестовая запись, собственно..."
      And I should not see "Параграф под катом"

  Scenario: Просмотр страницы записи
    Given I am on "/"
      And I follow "JavaScript, хоть и jQuery"
     Then I should not see "Читать далее"
      And I should not see "Тестовая запись, собственно..."
      And I should see "Параграф под катом"