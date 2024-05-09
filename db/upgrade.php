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
 * This file keeps track of upgrades to this plugin.
 *
 * @package    tool_userdebug
 * @author     Andreas Grabs <info@grabs-edv.de>
 * @copyright  2020 Andreas Grabs EDV-Beratung
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @param mixed $oldversion
 */

/**
 * Upgrade the plugin depending on the old and the new version.
 *
 * @param  int  $oldversion
 * @return bool
 */
function xmldb_tool_userdebug_upgrade($oldversion) {
    global $DB, $CFG;

    $dbman = $DB->get_manager();

    if ($oldversion < 2024050900) {

        $settings = [
            'users',
            'mode',
            'debugdisplay',
            'debugsmtp',
            'debugimap',
            'perfdebug',
            'debugstringids',
            'debugpageinfo',
        ];

        foreach ($settings as $setting) {
            $oldsetting = 'tool_userdebug_' . $setting;
            mtrace('convert setting: ' . $setting . ', with value: ' . ($CFG->{$oldsetting} ?? null));
            set_config($setting, $CFG->{$oldsetting} ?? null, 'tool_userdebug');
            set_config($oldsetting, null);
        }

        // Tool userdebug savepoint reached.
        upgrade_plugin_savepoint(true, 2024050900, 'tool', 'userdebug');
    }

    return true;
}
