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
    public static function set_adhoc_debug($on = true) {
        global $SESSION;

        if ($on) {
            $SESSION->userdebug = true;
        } else {
            unset($SESSION->userdebug);
        }
    }

    public static function is_adhoc_debug() {
        global $SESSION;

        return !empty($SESSION->userdebug);
    }

    public static function setdebug() {
        global $CFG, $USER, $SESSION;

        if ($userdebug = optional_param('userdebug', false, PARAM_BOOL)) {
            $context = \context_system::instance();
            if (has_capability('tool/userdebug:adhocdebug', $context)) {
                self::set_adhoc_debug(true);
            }
        }

        if (empty($CFG->tool_userdebug_users) AND empty($SESSION->userdebug)) {
            return;
        }

        if (NO_DEBUG_DISPLAY) {
            // Some parts of Moodle cannot display errors and debug at all.
            ini_set('display_errors', '0');
            ini_set('log_errors', '1');
        } else {
            $userids = explode(',', $CFG->tool_userdebug_users);
            if (in_array($USER->id, $userids) OR (!empty($SESSION->userdebug))) {
                self::enable_debugging();
            }
        }
    }

    public static function enable_debugging() {
        global $CFG;

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

    public static function get_settings_node() {
        global $PAGE;

        $returnpath = $PAGE->url;
        $url = new \moodle_url('/admin/tool/userdebug/adhocdebug.php', array('returnpath' => $returnpath));

        if (self::is_adhoc_debug()) {
            $pixicon = 'debugon';
            $strstate = 'on';
        } else {
            $pixicon = 'debugoff';
            $strstate = 'off';
        }
        $title = get_string('adhocdebug', 'tool_userdebug', $strstate);

        $settingsnode = \navigation_node::create(
            $title,
            $url,
            \navigation_node::TYPE_CUSTOM,
            null,
            null,
            new \pix_icon($pixicon, 'ad hoc debug', 'tool_userdebug')
        );

        return $settingsnode;
    }
}
