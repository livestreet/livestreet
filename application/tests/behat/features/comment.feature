Feature: Test Base comment functionality (!!!SELENIUM NEEDED)
  Test base functionality of Comments

  @mink:selenium2
  Scenario: Adding the comment

    Given I am on "/login"
    Then I want to login as "admin"

    Given I am on homepage
    Given I am on "/blog/3.html"

    Then I follow "Add comment"
    And I fill in "test comment" for "comment_text"
    And I press "Preview"
    Then I wait "1000"

    Then I should see in element by css "content .comment-preview" values:
    | value |
    | test comment |

    And I press "Add"
    Then I wait "1000"

    Then I should see in element by css "content .comment-content" values:
      | value |
      | test comment |

    Then I should see in element by css "content .comment-author" values:
      | value |
      | /profile/admin/">admin</a> |
    Then I should see in element by css "content .comment-actions" values:
      | value |
      | Reply |
      | Delete |
