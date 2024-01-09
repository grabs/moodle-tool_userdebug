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
 * Set the adhoc debug.
 *
 * @package    tool_userdebug
 * @author     Andreas Grabs <moodle@grabs-edv.de>
 * @copyright  2022 Andreas Grabs <moodle@grabs-edv.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use tool_userdebug\util;

require_once(__DIR__ . '/../../../config.php');

require_login();

// We use the realuser instead of the current user, so we can have debugging in "loginas" sessions too.
$realuser = \core\session\manager::get_realuser();

$context = \context_system::instance();
require_capability('tool/userdebug:adhocdebug', $context, $realuser);

$returnpath = optional_param('returnpath', '/', PARAM_URL);
$returnurl  = new \moodle_url($returnpath);

$myurl = new \moodle_url($FULLME);
$myurl->remove_all_params();

$PAGE->set_context($context);
$PAGE->set_url($myurl);

util::set_adhoc_debug(!util::is_adhoc_debug());
redirect($returnurl);
