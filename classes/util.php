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
 * @package    tool
 * @subpackage userdebug
 * @copyright  2018 Andreas Grabs <moodle@grabs-edv.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_userdebug;

defined('MOODLE_INTERNAL') || die;

class util {
    public static function setdebug() {
        global $CFG, $USER;

        if (empty($CFG->tool_userdebug_users)) {
            return;
        }

        if (NO_DEBUG_DISPLAY) {
            // Some parts of Moodle cannot display errors and debug at all.
            ini_set('display_errors', '0');
            ini_set('log_errors', '1');
        } else {
            $userids = explode(',', $CFG->tool_userdebug_users);
            if (in_array($USER->id, $userids)) {

                if (!empty($CFG->tool_userdebug_mode)) {
                    error_reporting($CFG->tool_userdebug_mode);
                    $CFG->debug = $CFG->tool_userdebug_mode;
                    $CFG->debugdeveloper = (($CFG->debug & DEBUG_DEVELOPER) === DEBUG_DEVELOPER);
                }

                $CFG->debugsmtp     = !empty($CFG->tool_userdebug_debugsmtp);
                $CFG->debugimap     = !empty($CFG->tool_userdebug_debugimap);
                $CFG->perfdebug     = !empty($CFG->tool_userdebug_perfdebug) ? $CFG->tool_userdebug_perfdebug : 0;
                $CFG->debugpageinfo = !empty($CFG->tool_userdebug_debugpageinfo);

                if (!empty($CFG->tool_userdebug_debugstringids)) {
                    $CFG->debugstringids = $CFG->tool_userdebug_debugstringids;
                    $_GET['strings'] = 1;
                }
                if (!empty($CFG->tool_userdebug_debugdisplay)) {
                    ini_set('display_errors', '1');
                    $CFG->debugdisplay = true;
                }
            }
        }
    }
}
