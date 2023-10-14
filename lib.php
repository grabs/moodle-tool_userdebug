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
 * Collection of hook function to manipulate the navigation and start the debug mode.
 *
 * @package    tool_userdebug
 * @author     Andreas Grabs <moodle@grabs-edv.de>
 * @copyright  2022 Andreas Grabs <moodle@grabs-edv.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use tool_userdebug\util;

defined('MOODLE_INTERNAL') || die;

if (defined('ABORT_AFTER_CONFIG')) {
    return;
}

/**
 * Hook function is called at the end of setup.php.
 *
 * @return void
 */
function tool_userdebug_after_config() {
    tool_userdebug\util::setdebug();
}

/**
 * Allow plugins to provide some content to be rendered in the navbar.
 * The plugin must define a PLUGIN_render_navbar_output function that returns
 * the HTML they wish to add to the navbar.
 *
 * @return string HTML for the navbar
 */
function tool_userdebug_render_navbar_output() {
    if (!has_capability('tool/userdebug:adhocdebug', \context_system::instance())) {
        return '';
    }
    return util::create_nav_action();
}

/**
 * This function extends the frontpage navigation.
 *
 * @param navigation_node $parentnode The navigation node to extend
 * @param stdClass        $course     The course to object for the tool
 * @param context_course  $context    The context of the course
 */
function tool_userdebug_extend_navigation_frontpage(navigation_node $parentnode, stdClass $course, context_course $context) {
    if (!has_capability('tool/userdebug:adhocdebug', \context_system::instance())) {
        return;
    }
    $parentnode->add_node(util::get_settings_node());
}

/**
 * Extend the user settings navigation.
 *
 * @param  navigation_node $parentnode
 * @param  stdClass        $user
 * @param  context_user    $context
 * @param  stdClass        $course
 * @param  context_course  $coursecontext
 * @return void
 */
function tool_userdebug_extend_navigation_user_settings(navigation_node $parentnode, stdClass $user, context_user $context,
    stdClass $course, context_course $coursecontext) {
    if (!has_capability('tool/userdebug:adhocdebug', \context_system::instance())) {
        return;
    }

    $parentnode->add_node(util::get_settings_node());
}

/**
 * Get icon mapping for FontAwesome.
 *
 * @return array
 */
function tool_userdebug_get_fontawesome_icon_map() {
    // We build a map of some icons we use in the flatnav.
    $iconmap = [
        'tool_userdebug:debugon'  => 'fa-bug text-danger',
        'tool_userdebug:debugoff' => 'fa-bug',
    ];

    return $iconmap;
}
