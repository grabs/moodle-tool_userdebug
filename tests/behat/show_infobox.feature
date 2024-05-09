@tool @tool_userdebug

Feature: Show user debug infobox
  In order to switch to adhoc debugging

  Background:
    Given the following config values are set as admin:
      | mode           | 32767 | tool_userdebug |
      | debugdisplay   | 1     | tool_userdebug |
      | debugstringids | 1     | tool_userdebug |
      | perfdebug      | 15    | tool_userdebug |
    And the following "roles" exist:
      | name      | shortname | description | archetype |
      | DebugUser | debuguser | debuguser   |           |
    And the following "permission overrides" exist:
      | capability                   | permission | role      | contextlevel | reference |
      | tool/userdebug:adhocdebug    | Allow      | debuguser | System       |           |
    And the following "users" exist:
      | username | firstname | lastname | email             |
      | user1    | user1     | user1    | user1@example.com |
    And the following "role assigns" exist:
      | user  | role      | contextlevel | reference |
      | user1 | debuguser | System       |           |

  @javascript
  Scenario: See the user debug infobox.
    When I log in as "admin"
    And I navigate to "Development > User related debugmodus" in site administration
    And I should see "user1" in the "#addselect" "css_element"
    And I set the field "addselect" to "user1 user1 (user1@example.com)"
    And I press "Add"
    And I should see "user1" in the "#removeselect" "css_element"
    And I log out
    And I log in as "user1"
    # Check that I see the infobox
    And I should see "User debug mode is active!"

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

    # Check that I don't see the the infobox
    And I should not see "User debug mode is active!"
