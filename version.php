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
 * Version definition.
 *
 * @package    tool_userdebug
 * @author     Andreas Grabs <moodle@grabs-edv.de>
 * @copyright  2022 Andreas Grabs <moodle@grabs-edv.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

$plugin->version   = 2025070100;
$plugin->release   = 'v5.0.1 (2025070100)';
$plugin->requires  = 2025040800;
$plugin->maturity  = MATURITY_STABLE;
$plugin->component = 'tool_userdebug';
