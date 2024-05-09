@tool @tool_userdebug

Feature: Switch to adhoc debugging
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
  Scenario: See the Ad hoc debug link on the user preferences page.
    And I log in as "user1"
    When I follow "Preferences" in the user menu
    And I should see "Ad hoc debug (off)"
    And I click on "Ad hoc debug (off)" "link"
    # I should see the string identifiers too.
    And I should see "Ad hoc debug (on) {adhocdebug/tool_userdebug}"
    # Check that I see the string identifiers
    And I should see "Home {home/}"
    And I should see "Dashboard {myhome/}"
    # Check that I see the performance infos
    And I should see "get_string calls:"
    And I should see "Strings filtered:"
    And I should see "DB queries time:"
    # Disable ad hoc debug
    And I click on "Ad hoc debug (on) {adhocdebug/tool_userdebug}" "link"
    Then I should see "Ad hoc debug (off)"

  @javascript
  Scenario: Enabled Ad hoc debug is disabled after logout and login
    And I log in as "user1"
    When I follow "Preferences" in the user menu
    And I should see "Ad hoc debug (off)"
    And I click on "Ad hoc debug (off)" "link"
    And I should see "Ad hoc debug (on) {adhocdebug/tool_userdebug}"
    # Check that I see the string identifiers
    And I should see "Home {home/}"
    And I should see "Dashboard {myhome/}"
    # Check that I see the performance infos
    And I should see "get_string calls:"
    And I should see "Strings filtered:"
    And I should see "DB queries time:"
    # Disable ad hoc debug by logging out
    And I log out
    And I log in as "user1"
    And I follow "Preferences" in the user menu
    Then I should see "Ad hoc debug (off)"
