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
 * Print the settings page.
 *
 * @package    tool_userdebug
 * @author     Andreas Grabs <moodle@grabs-edv.de>
 * @copyright  2022 Andreas Grabs <moodle@grabs-edv.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/' . $CFG->admin . '/tool/userdebug/lib.php');
require_once($CFG->dirroot . '/' . $CFG->admin . '/roles/lib.php');

admin_externalpage_setup('tool_userdebug', '', null);
if (!is_siteadmin()) {
    die;
}

$mycfg = get_config('tool_userdebug');

if (empty($mycfg->users)) {
    $mycfg->users = '';
}

$settingsform = new \tool_userdebug\settingsform();

$debuguserselector          = new \tool_userdebug\selector();
$potentialdebuguserselector = new \tool_userdebug\potential_selector();

$formdata = $settingsform->get_data();

if (optional_param('add', false, PARAM_BOOL) && confirm_sesskey()) {
    if ($userstoadd = $potentialdebuguserselector->get_selected_users()) {
        $usertoadd  = reset($userstoadd);
        $debugusers = [];
        foreach (explode(',', $mycfg->users) as $du) {
            $du = (int) $du;
            if ($du) {
                $debugusers[$du] = $du;
            }
        }

        $debugusers[$usertoadd->id] = $usertoadd->id;
        set_config('users', implode(',', $debugusers), 'tool_userdebug');
        redirect($PAGE->url);
        die;
    }
} else if (optional_param('remove', false, PARAM_BOOL) && confirm_sesskey()) {
    if ($userstoremove = $debuguserselector->get_selected_users()) {
        $usertoremove = reset($userstoremove);
        $debugusers   = [];
        foreach (explode(',', $mycfg->users) as $du) {
            $du = (int) $du;
            if ($du) {
                $debugusers[$du] = $du;
            }
        }
        unset($debugusers[$usertoremove->id]);
        set_config('users', implode(',', $debugusers), 'tool_userdebug');
        redirect($PAGE->url);
    }
}

if (!empty($formdata->savechanges)) {
    $debugmode                      = !empty($formdata->debugmode) ? $formdata->debugmode : 0;
    $debugdisplay                   = !empty($formdata->debugdisplay);
    $debugsmtp                      = !empty($formdata->debugsmtp);
    $debugimap                      = !empty($formdata->debugimap);
    $perfdebug                      = !empty($formdata->perfdebug) ? 15 : 0; // Perfdebug needs another value than 1.
    $debugstringids                 = !empty($formdata->debugstringids);
    $debugpageinfo                  = !empty($formdata->debugpageinfo);
    $showdeprecatedstyle = !empty($formdata->showdeprecatedstyle);

    if ($debugmode !== false) {
        set_config('mode', $debugmode, 'tool_userdebug');
        set_config('debugdisplay', $debugdisplay, 'tool_userdebug');
        set_config('debugsmtp', $debugsmtp, 'tool_userdebug');
        set_config('debugimap', $debugimap, 'tool_userdebug');
        set_config('perfdebug', $perfdebug, 'tool_userdebug');
        set_config('debugstringids', $debugstringids, 'tool_userdebug');
        set_config('debugpageinfo', $debugpageinfo, 'tool_userdebug');
        set_config('showdeprecatedstyle', $showdeprecatedstyle, 'tool_userdebug');
    }
    redirect($PAGE->url, get_string('changessaved'), 3);
}

$selectusers   = new \tool_userdebug\output\selectusers($PAGE->url, $debuguserselector, $potentialdebuguserselector);
$debugsettings = new \tool_userdebug\output\debugsettings($settingsform);

// Print header.
echo $OUTPUT->header();

echo $OUTPUT->render($debugsettings);
echo $OUTPUT->render($selectusers);

echo $OUTPUT->footer();
