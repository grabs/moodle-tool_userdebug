<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Unit tests for tool_userdebug hook feature.
 *
 * @package     tool_userdebug
 * @category    test
 * @author      Andreas Grabs <info@grabs-edv.de>
 * @copyright   2018 onwards Grabs EDV {@link https://www.grabs-edv.de}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_userdebug;

/**
 * Unit tests for general invitation features.
 *
 * @package     tool_userdebug
 * @category    test
 * @author      Andreas Grabs <info@grabs-edv.de>
 * @copyright   2018 onwards Grabs EDV {@link https://www.grabs-edv.de}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class hook_test extends \advanced_testcase {

    /**
     * Set up the test.
     *
     * @return void
     */
    protected function setUp(): void {
        global $PAGE;

        if (!defined('TOOL_USERDEBUG_RUN_IN_PHPUNIT_TEST')) {
            define('TOOL_USERDEBUG_RUN_IN_PHPUNIT_TEST', true);
        }

        parent::setUp();

        $PAGE->set_url(new \moodle_url('/'));
        $this->resetAfterTest();
        $this->setAdminUser();
    }

    /**
     * Test getting the ad hoc debug node.
     *
     * @covers \tool_userdebug\util
     * @return void
     */
    public function test_add_menuuser(): void {
        // Get the ad hoc debug node.
        $navitems = \tool_userdebug\util::add_menuuser();

        // Check the node includes all needed elements.
        $title = get_string('adhocdebug', 'tool_userdebug', 'off');
        $this->assertEquals('link', $navitems->itemtype);
        $this->assertStringContainsString($title, $navitems->title);
        $this->assertInstanceOf('moodle_url', $navitems->url);
    }

    /**
     * Test rendering the ad hoc debug node.
     *
     * @covers \tool_userdebug\util
     * @return void
     */
    public function test_extend_user_menu_hook(): void {
        global $OUTPUT, $USER;

        // Trigger the extend_user_menu hook.
        $output = $OUTPUT->user_menu($USER);

        // Check whether the output includes the ad hoc debug node.
        $title = get_string('adhocdebug', 'tool_userdebug', 'off');
        $this->assertStringContainsString($title, $output);
    }

}
