Запуск функциональных тестов
============================

Для запуска тестов проекта нужно:

1) Переименовать файл config/config.test.php.dist в config/config.test.php и изменить настройки подключения к тестовой БД.
ВАЖНО! Информация в этой БД будет перезаписываться при каждом запуске теста.

2) В конфиге для Behat (tests/behat/behat.yml) сменить значение опции base_url на хост, под которым проект доступен локально.

3) Выполнить команду ```cd tests/behat; HTTP_APP_ENV=test php behat.phar```. Примерный вывод результата работы команды:

```
DROP DATABASE social_test
CREATE DATABASE social_test
SELECTED DATABASE social_test
ExportSQL DATABASE social_test
ExportSQL DATABASE social_test -> geo_base
Feature: LiveStreet standart features
  Test base functionality of LiveStreet

  Scenario: See main page                       # features/base.feature:4
    Given I am on homepage                      # FeatureContext::iAmOnHomepage()
    When I press "Войти"                        # FeatureContext::pressButton()
    Then the response status code should be 200 # FeatureContext::assertResponseStatus()

  Scenario: See Colective Blog                       # features/base.feature:9
    Given I am on "/blog/gadgets"                    # FeatureContext::visit()
    Then I should see "Gadgets"                      # FeatureContext::assertPageContainsText()
    Then I should see "Offers latest gadget reviews" # FeatureContext::assertPageContainsText()

  Scenario: See list of blogs                        # features/base.feature:14
    Given I am on "/blogs/"                          # FeatureContext::visit()
    Then I should see "Gadgets"                      # FeatureContext::assertPageContainsText()

  Scenario: See All Topic                                                                 # features/base.feature:18
    Given I am on "/index/newall/"                                                        # FeatureContext::visit()
    Then I should see "iPad 3 rumored to come this March with quad-core chip and 4G LTE " # FeatureContext::assertPageContainsText()
    Then I should see "Toshiba unveils 13.3-inch AT330 Android ICS 4.0 tablet"            # FeatureContext::assertPageContainsText()

  Scenario: See User Profile                                                              # features/base.feature:23
    Given I am on "/profile/Golfer/"                                                      # FeatureContext::visit()
    Then I should see "Sergey Doryba"                                                     # FeatureContext::assertPageContainsText()
    Then I should see "... Sergey Doryba profile description"                             # FeatureContext::assertPageContainsText()

5 scenarios (5 passed)
14 steps (14 passed)
0m2.225s
```