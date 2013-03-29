Feature: backendPost
  Add post, edit post

  Scenario: Add post
    Given I am logged in as Admin
     When I am on "/admin/topic"
     Then I should see "Управление записями"
     When I follow "Новая запись"
     Then I should see "Создание записи"
     When I fill in "Заголовок:" with "Публий Корнелий Тацит"
      And I fill in "Description:" with "проверка meta-description"
      And I fill in "Текст записи:" with "<p>В молодости Тацит совмещал карьеру судебного оратора с политической деятельностью, стал сенатором, а в 97 году добился высшей магистратуры консула.</p>"
      And I fill in "Теги:" with "new_post_tag, new_post_tag_2"
      And I press "Создать запись"
     Then I should see "Запись создана"
      And restore database