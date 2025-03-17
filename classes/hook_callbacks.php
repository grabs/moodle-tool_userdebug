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
use core_user\hook\extend_user_menu;
use core\hook\after_config;


/**
 * Callbacks for hooks.
 *
 * @package    tool_userdebug
 * @copyright  2024 Andreas Grabs <moodle@grabs-edv.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class hook_callbacks {
    /**
     * Listener for the after_config hook.
     *
     * @param after_config $hook
     */
    public static function after_config(after_config $hook): void {
        global $CFG;

        if (during_initial_install() || isset($CFG->upgraderunning)) {
            // Do nothing during installation or upgrade.
            return;
        }

        \tool_userdebug\util::setdebug();
    }

    /**
     * This is the hook enables the plugin to add one or more menu item.
     *
     * @param extend_user_menu $hook
     */
    public static function extend_user_menu(extend_user_menu $hook): void {
        $navitems = util::add_menuuser();
        $hook->add_navitem($navitems);
    }
}
