Feature: LiveStreet standart features
  Test base functionality of LiveStreet

  Scenario: See main page
    Given I am on homepage
    Then the response status code should be 200

    Then I should see "Sony MicroVault Mach USB 3.0 flash drive"
    Then I should see "Blogger's name user-golfer"

    Then I should see "iPad 3 rumored to come this March with quad-core chip and 4G LTE "
    Then I should see "Toshiba unveils 13.3-inch AT330 Android ICS 4.0 tablet"
    Then I should see "Gadgets"

  Scenario: See colective blog
    Given I am on "/blog/gadgets"
    Then the response status code should be 200

    Then I should see "Gadgets"
    Then I should see "Offers latest gadget reviews"

  Scenario: See list of all blogs
    Given I am on "/blogs/"
    Then the response status code should be 200

    Then I should see "Gadgets"
    Then I should see "user-golfer"

  Scenario: See all new topics
    Given I am on "/index/newall/"
    Then the response status code should be 200

    Then I should see "Sony MicroVault Mach USB 3.0 flash drive"
    Then I should see "iPad 3 rumored to come this March with quad-core chip and 4G LTE "
    Then I should see "Toshiba unveils 13.3-inch AT330 Android ICS 4.0 tablet"

  Scenario: See user profile
    Given I am on "/profile/user-golfer/"
    Then the response status code should be 200

    Then I should see "user-golfer"
    Then I should see "... Golfer profile description"