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
use tool_userdebug\selector;
use tool_userdebug\potential_selector;

defined('MOODLE_INTERNAL') || die;

class selectusers implements \templatable, \renderable {
    private $data;

    public function __construct($url, selector $userselector, potential_selector $potentialuserselector) {
        $this->data = new \stdClass();
        $this->data->formurl = $url;
        $this->data->sesskey = sesskey();
        $this->data->userselectbox = $userselector->display(true);
        $this->data->potentialuserselectbox = $potentialuserselector->display(true);

    }

    public function export_for_template(\renderer_base $output) {
        return $this->data;
    }
}
