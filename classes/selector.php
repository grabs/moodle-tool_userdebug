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

class selector extends \user_selector_base {

    /**
     * Constructor
     */
    public function __construct() {
        global $CFG, $USER;
        parent::__construct('removeselect', array('multiselect' => false));
    }

    /**
     * Searches the potential users.
     *
     * @param string $search
     * @return array()
     */
    public function find_users($search) {
        global $DB, $CFG;
        list($wherecondition, $params1) = $this->search_sql($search, '');

        $fields      = 'SELECT ' . $this->required_fields_sql('');
        $countfields = 'SELECT COUNT(1)';

        $tooluserdebugusers = clean_param_array(explode(',', $CFG->tool_userdebug_users), PARAM_INT);
        list($debugid, $params2) = $DB->get_in_or_equal($tooluserdebugusers, SQL_PARAMS_NAMED, 'val');

        if ($wherecondition) {
            $wherecondition = "$wherecondition AND id $debugid";
        } else {
            $wherecondition = "id $debugid";
        }
        $sql = " FROM {user}
                WHERE $wherecondition";
        $order = ' ORDER BY lastname ASC, firstname ASC';

        $params = array_merge($params1, $params2);

        $availableusers = $DB->get_records_sql($fields . $sql . $order, $params);

        if (empty($availableusers)) {
            return array();
        }

        $result = array();

        if ($availableusers) {
            if ($search) {
                $groupname = get_string('extusersmatching', 'role', $search);
            } else {
                $groupname = get_string('extusers', 'role');
            }
            $result[$groupname] = $availableusers;
        }

        return $result;
    }

    /**
     * Returns an options array.
     *
     * @return array()
     */
    protected function get_options() {
        global $CFG;
        $options = parent::get_options();
        $options['file'] = $CFG->admin . '/roles/lib.php';
        return $options;
    }
}
