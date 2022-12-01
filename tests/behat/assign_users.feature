@tool @tool_userdebug

Feature: Assign users to user related debugging
  In order to switch to adhoc debugging

  Background:
    Given the following config values are set as admin:
      | tool_userdebug_mode           | 32767 |
      | tool_userdebug_debugdisplay   | 1     |
      | tool_userdebug_debugstringids | 1     |
      | tool_userdebug_perfdebug      | 15    |

    And the following "users" exist:
      | username | firstname | lastname | email             |
      | user1    | user1     | user1    | user1@example.com |
      | user2    | user2     | user2    | user2@example.com |

  @javascript
  Scenario: See the Ad hoc debug link on the user preferences page.
    When I log in as "admin"
    And I navigate to "Development > User related debugmodus" in site administration
    And I should see "user1" in the "#addselect" "css_element"
    And I set the field "addselect" to "user1 user1 (user1@example.com)"
    And I press "Add"
    And I should see "user1" in the "#removeselect" "css_element"
    And I log out
    And I log in as "user1"
    # Check that I see the string identifiers
    And I should see "Home {home/}"
    And I should see "Dashboard {myhome/}"
    # Check that I see the performance infos
    And I should see "get_string calls:"
    And I should see "Strings filtered:"
    And I should see "DB queries time:"

    # Remove the user from userdebug settings.
    And I log out
    And I log in as "admin"
    And I navigate to "Development > User related debugmodus" in site administration
    And I should see "user1" in the "#removeselect" "css_element"
    And I set the field "removeselect" to "user1 user1 (user1@example.com)"
    And I press "Remove"
    And I should see "user1" in the "#addselect" "css_element"
    And I log out
    And I log in as "user1"

    # Check that I don't see the string identifiers
    And I should not see "Home {home/}"
    # Check that I don't see the performance infos
    Then I should not see "DB queries time:"
