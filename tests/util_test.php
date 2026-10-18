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

namespace tool_userdebug;

/**
 * Unit tests for the ad hoc debug handling in {@see util}.
 *
 * @package    tool_userdebug
 * @category   test
 * @copyright  2026 ISB Bayern
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
#[\PHPUnit\Framework\Attributes\CoversClass(util::class)]
final class util_test extends \advanced_testcase {
    /**
     * Create a user holding the ad hoc debug capability and enable the ad hoc debug mode for this user.
     *
     * @return array The user and the id of the assigned role.
     */
    private function setup_adhoc_debug_user(): array {
        $this->resetAfterTest();
        set_config('debugpageinfo', 1, 'tool_userdebug');

        $user = $this->getDataGenerator()->create_user();
        $roleid = $this->getDataGenerator()->create_role();
        $context = \context_system::instance();
        assign_capability('tool/userdebug:adhocdebug', CAP_ALLOW, $roleid, $context->id);
        role_assign($roleid, $user->id, $context->id);
        $this->setUser($user);

        util::set_adhoc_debug(true);
        return [$user, $roleid];
    }

    /**
     * The ad hoc debug mode must end once the capability has been revoked.
     *
     * @return void
     */
    public function test_setdebug_after_capability_revoked(): void {
        global $CFG;

        [$user, $roleid] = $this->setup_adhoc_debug_user();
        role_unassign($roleid, $user->id, \context_system::instance()->id);

        util::setdebug();

        $this->assertFalse(util::is_adhoc_debug());
        $this->assertEmpty($CFG->debugpageinfo ?? false);
    }

    /**
     * The ad hoc debug mode stays active while the capability is held.
     *
     * @return void
     */
    public function test_setdebug_with_capability(): void {
        global $CFG;

        $this->setup_adhoc_debug_user();

        util::setdebug();

        $this->assertTrue(util::is_adhoc_debug());
        $this->assertTrue($CFG->debugpageinfo);
    }
}
