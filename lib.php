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
use tool_userdebug\util;

defined('MOODLE_INTERNAL') || die;

if (defined('ABORT_AFTER_CONFIG')) {
    return;
}

function tool_userdebug_after_config() {
    tool_userdebug\util::setdebug();
}

/**
 * This function extends the frontpage navigation
 *
 * @param navigation_node $navigation The navigation node to extend
 * @param stdClass        $course     The course to object for the tool
 * @param context         $context    The context of the course
 */
function tool_userdebug_extend_navigation_frontpage(navigation_node $parentnode, stdClass $course, context_course $context) {
    global $PAGE;

    if (!has_capability('tool/userdebug:adhocdebug', \context_system::instance())) {
        return;
    }
    $parentnode->add_node(util::get_settings_node());
}

function tool_userdebug_extend_navigation_user_settings(navigation_node $parentnode, stdClass $user, context_user $context,
                                                        stdClass $course, context_course $coursecontext) {
    global $PAGE;

    if (!has_capability('tool/userdebug:adhocdebug', \context_system::instance())) {
        return;
    }

    $parentnode->add_node(util::get_settings_node());
}

/**
 * Get icon mapping for FontAwesome.
 */
function tool_userdebug_get_fontawesome_icon_map() {
    // We build a map of some icons we use in the flatnav.
    $iconmap = array(
        'tool_userdebug:debugon' => 'fa-bug text-success',
        'tool_userdebug:debugoff' => 'fa-bug text-error',
    );

    return $iconmap;
}
