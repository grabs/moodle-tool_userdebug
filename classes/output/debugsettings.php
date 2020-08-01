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

namespace tool_userdebug\output;

defined('MOODLE_INTERNAL') || die;

class debugsettings implements \templatable, \renderable {
    private $data;

    public function __construct(\tool_userdebug\settingsform $form) {
        $this->data = new \stdClass();
        $this->data->form = $form->get_output();
        $this->data->open = !empty($_COOKIE["debugsettingsopen"]) ? $_COOKIE["debugsettingsopen"] : '';
    }

    public function export_for_template(\renderer_base $output) {
        return $this->data;
    }
}
