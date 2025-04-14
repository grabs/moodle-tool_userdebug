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
 * Utility class to manage the user defined debuging mode.
 *
 * @package    tool_userdebug
 * @copyright  2022 Andreas Grabs <moodle@grabs-edv.de>
 * @author     Andreas Grabs <moodle@grabs-edv.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class util {
    /**
     * Set the adhoc debug mode on or off.
     *
     * @param  bool $on
     * @return void
     */
    public static function set_adhoc_debug($on = true) {
        global $SESSION;

        if ($on) {
            $SESSION->userdebug = true;
        } else {
            unset($SESSION->userdebug);
        }
    }

    /**
     * Check whether or not the adhoc debug mode is active.
     *
     * @return bool
     */
    public static function is_adhoc_debug() {
        global $SESSION;

        return !empty($SESSION->userdebug);
    }

    /**
     * Activate the debug mode for the current user if he is defined in $mycfg->users {@see:static::enable_debugging}.
     *
     * @return void
     */
    public static function setdebug() {
        global $SESSION;
        $mycfg = get_config('tool_userdebug');

        $debugactive = false;

        // We use the realuser instead of the current user, so we can have debugging in "loginas" sessions too.
        $realuser = \core\session\manager::get_realuser();

        if ($userdebug = optional_param('userdebug', false, PARAM_BOOL)) {
            $context = \context_system::instance();
            if (has_capability('tool/userdebug:adhocdebug', $context, $realuser)) {
                static::set_adhoc_debug(true);
            }
        }

        if (!empty($mycfg->users)) {
            $userids = explode(',', $mycfg->users);
            if (in_array($realuser->id, $userids, true)) {
                $debugactive = true;
            }
        }
        if (!empty($SESSION->userdebug)) {
            $debugactive = true;
        }

        if (!$debugactive) {
            return;
        }

        $debugcfg = static::enable_debugging();

        if (defined('NO_DEBUG_DISPLAY') && NO_DEBUG_DISPLAY) {
            // Some parts of Moodle cannot display errors and debug at all.
            ini_set('display_errors', '0');
            ini_set('log_errors', '1');
        }

        // After debugging is set, we display a nice info box to show the user, some infos about the debug mode.
        if (!AJAX_SCRIPT && !WS_SERVER) {
            global $PAGE;

            // Prepare the content for the infobox.
            $debugsettings = [];
            $infocontent = new \stdClass();
            foreach ($debugcfg as $key => $value) {
                if (!empty($value)) {
                    $showdebuginfo = true;
                }
                $debugsettings[] = (object) [
                    'key' => $key,
                    'value' => $value,
                ];
            }

            // Show the debug info box only if at leas one debug setting is active or the adhoc debug mode is used.
            if (!empty($showdebuginfo) || static::is_adhoc_debug()) {
                $infocontent->debugsettings = $debugsettings;
                if (is_siteadmin()) { // For site admins the link to the setting should be shown.
                    $manageurl = new \moodle_url('/admin/tool/userdebug/index.php');
                    $infocontent->manageurl = $manageurl->out();
                }
                // Load the box by javascript.
                $PAGE->requires->js_call_amd('tool_userdebug/debuginfo', 'init', [$infocontent]);
            }
        }

    }

    /**
     * Enables the debugging.
     * All defined settings will be stored in $CFG and also in $CFG->config_php_settings to prevent saving this for other users.
     *
     * @return \stdClass
     */
    public static function enable_debugging() {
        $mycfg = get_config('tool_userdebug');

        $debugcfg = new \stdClass();

        if (!empty($mycfg->mode)) {
            error_reporting($mycfg->mode);
            $debugcfg->debug          = $mycfg->mode;
            $debugcfg->debugdeveloper = (($debugcfg->debug & DEBUG_DEVELOPER) === DEBUG_DEVELOPER);
        }

        $debugcfg->debugsmtp     = !empty($mycfg->debugsmtp);
        $debugcfg->debugimap     = !empty($mycfg->debugimap);
        $debugcfg->perfdebug     = !empty($mycfg->perfdebug) ? $mycfg->perfdebug : 0;
        $debugcfg->debugpageinfo = !empty($mycfg->debugpageinfo);

        if (!empty($mycfg->debugstringids)) {
            $debugcfg->debugstringids = $mycfg->debugstringids;
            $_GET['strings']          = 1;
        }
        if (!empty($mycfg->debugdisplay)) {
            ini_set('display_errors', '1');
            $debugcfg->debugdisplay = true;
        }

        static::set_debug_in_cfg($debugcfg);
        return $debugcfg;
    }

    /**
     * This put all CFG values actually in the $CFG and $CFG->config_php_settings.
     *
     * @param  \stdClass $debugcfg
     * @return void
     */
    private static function set_debug_in_cfg($debugcfg) {
        global $CFG;

        foreach ($debugcfg as $setting => $value) {
            $CFG->{$setting} = $value;
        }

        if (empty($CFG->config_php_settings)) {
            $CFG->config_php_settings = [];
        }
        foreach ($debugcfg as $setting => $value) {
            $CFG->config_php_settings[$setting] = $value;
        }
    }

    /**
     * Create a settings node for the extended navigation.
     *
     * @return \navigation_node
     */
    public static function get_settings_node() {
        global $PAGE;

        $returnpath = $PAGE->url;
        $url        = new \moodle_url('/admin/tool/userdebug/adhocdebug.php', ['returnpath' => $returnpath]);

        if (static::is_adhoc_debug()) {
            $pixicon  = 'debugon';
            $strstate = 'on';
        } else {
            $pixicon  = 'debugoff';
            $strstate = 'off';
        }
        $title = get_string('adhocdebug', 'tool_userdebug', $strstate);

        $settingsnode = \navigation_node::create(
            $title,
            $url,
            \navigation_node::TYPE_CUSTOM,
            null,
            null,
            new \pix_icon($pixicon, $title, 'tool_userdebug')
        );

        return $settingsnode;
    }

    /**
     * Create a rendered action element for user navigation (Top navigation left from user avatar).
     *
     * @return string The html
     */
    public static function create_nav_action() {
        global $OUTPUT;

        if (!$navigationnode = static::get_settings_node()) {
            return '';
        }

        $content = new \stdClass();
        $content->text = $navigationnode->text;
        $content->url = $navigationnode->action;
        $content->icon = $OUTPUT->render($navigationnode->icon);
        return $OUTPUT->render_from_template('tool_userdebug/navbar_action', $content);
    }

    /**
     * Add an item in the navigation menu.
     *
     * @return ?\stdClass The navigation item.
     */
    public static function add_menuuser(): ?\stdClass {
        // Don't add the menuitem if PHPUNIT_TEST is running except we run it self.
        if (defined('PHPUNIT_TEST') && PHPUNIT_TEST && !defined('TOOL_USERDEBUG_RUN_IN_PHPUNIT_TEST')) {
            return null;
        }

        if (!$navigationnode = static::get_settings_node()) {
            return null;
        }

        $content = new \stdClass();
        $content->itemtype = 'link';
        $content->title = $navigationnode->text;
        $content->url = $navigationnode->action;

        return $content;
    }
}
