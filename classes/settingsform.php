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

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/user/selector/lib.php');

class settingsform extends \moodleform {
    public function definition() {
        global $CFG;

        $mform =& $this->_form;

        $mform->addElement('header', 'headergeneral', get_string('debug', 'admin'));

        $debugmode = array(DEBUG_NONE      => get_string('debugnone', 'admin'),
            DEBUG_MINIMAL   => get_string('debugminimal', 'admin'),
            DEBUG_NORMAL    => get_string('debugnormal', 'admin'),
            DEBUG_ALL       => get_string('debugall', 'admin'),
            DEBUG_DEVELOPER => get_string('debugdeveloper', 'admin'));

        $mform->addElement('select', 'debugmode', get_string('debugmode', 'tool_userdebug'), $debugmode);
        $mform->setType('debugmode', PARAM_INT);
        if (!empty($CFG->tool_userdebug_mode)) {
            $mform->setDefault('debugmode', $CFG->tool_userdebug_mode);
        }

        $mform->addElement(
            'checkbox',
            'debugdisplay',
            get_string('debugdisplay', 'admin')
        );
        $mform->setDefault('debugdisplay', !empty($CFG->tool_userdebug_debugdisplay));

        $mform->addElement(
            'checkbox',
            'debugsmtp',
            get_string('debugsmtp', 'tool_userdebug')
        );
        $mform->setDefault('debugsmtp', !empty($CFG->tool_userdebug_debugsmtp));

        $mform->addElement(
            'checkbox',
            'debugimap',
            get_string('debugimap', 'tool_userdebug')
        );
        $mform->setDefault('debugimap', !empty($CFG->tool_userdebug_debugimap));

        $mform->addElement(
            'checkbox',
            'perfdebug',
            get_string('perfdebug', 'admin')
        );
        $mform->setDefault('perfdebug', !empty($CFG->tool_userdebug_perfdebug));

        $mform->addElement(
            'checkbox',
            'debugstringids',
            get_string('debugstringids', 'admin')
        );
        $mform->setDefault('debugstringids', !empty($CFG->tool_userdebug_debugstringids));

        $mform->addElement(
            'checkbox',
            'debugpageinfo',
            get_string('debugpageinfo', 'admin')
        );
        $mform->setDefault('debugpageinfo', !empty($CFG->tool_userdebug_debugpageinfo));

        // Add the save button.
        $mform->addElement('submit', 'savechanges', get_string('savechanges'));
    }

    public function get_output() {
        ob_start();
        $this->display();
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
}
