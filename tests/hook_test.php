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
use core_user\hook\extend_user_menu;

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
    public function test_add_menu_icon(): void {
        global $PAGE;

        $PAGE->set_url(new \moodle_url('/'));
        $PAGE->set_context(\context_system::instance());

        $output = $PAGE->get_renderer('core', 'admin');

        // Create an empty hook object.
        $hook = new extend_user_menu([]);

        // Add the meno icon to the hook object.
        \tool_userdebug\util::add_menu_icon($hook);

        // To check the node is created, we prepare a new menu item.
        // Since Moodle 5.3 the new methode "get_menu_items" is introduced.
        if (method_exists($hook, 'get_menu_items')) {
            $items = $hook->get_menu_items();
            $item = (object) $items[0]->export_for_template($output);
        } else {
            $items = $hook->get_navitems();
            $item = $items[0];
        }
        $title = get_string('adhocdebug', 'tool_userdebug', 'off');

        // Check the attributes are ok.
        $this->assertEquals(1, count($items));
        $this->assertEquals('link', $item->itemtype);
        $this->assertEquals($title, $item->title);
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

    #[\PHPUnit\Framework\Attributes\Group('baseline')]
    /**
     * The userdebug url parameter must not enable the ad hoc debug mode without a valid sesskey.
     *
     * @covers \tool_userdebug\util::setdebug
     * @return void
     */
    public function test_setdebug_without_sesskey(): void {
        $_GET['userdebug'] = 1;
        util::setdebug();
        unset($_GET['userdebug']);

        $this->assertFalse(util::is_adhoc_debug());
    }

    #[\PHPUnit\Framework\Attributes\Group('baseline')]
    /**
     * The userdebug url parameter enables the ad hoc debug mode with a valid sesskey.
     *
     * @covers \tool_userdebug\util::setdebug
     * @return void
     */
    public function test_setdebug_with_sesskey(): void {
        $_GET['userdebug'] = 1;
        $_GET['sesskey'] = sesskey();
        util::setdebug();
        unset($_GET['userdebug'], $_GET['sesskey']);

        $this->assertTrue(util::is_adhoc_debug());
        $this->assertStringContainsString('sesskey=' . sesskey(), util::get_settings_node()->action->out(false));
    }
}
