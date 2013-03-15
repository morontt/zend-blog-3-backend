Prepare DB

php app/console  mtt:database:prepare -e test
php app/console  mtt:database:prepare -e test --without-fixtures

Start test

sh bin/behat @MttBlogBundle