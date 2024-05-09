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
 * User selector for potential users.
 *
 * @package    tool_userdebug
 * @author     Andreas Grabs <moodle@grabs-edv.de>
 * @copyright  2022 Andreas Grabs <moodle@grabs-edv.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class potential_selector extends \user_selector_base {
    /**
     * Constructor.
     */
    public function __construct() {
        $mycfg = get_config('tool_userdebug');
        $admins = clean_param_array(explode(',', $mycfg->users ?? ''), PARAM_INT);
        parent::__construct('addselect', ['multiselect' => false, 'exclude' => $admins]);
    }

    /**
     * Searches the potential users.
     *
     * @param  string  $search
     * @return array()
     */
    public function find_users($search) {
        global $CFG, $DB;
        list($wherecondition, $params) = $this->search_sql($search, '');

        $fields      = 'SELECT ' . $this->required_fields_sql('');
        $countfields = 'SELECT COUNT(1)';

        $sql = " FROM {user}
                WHERE $wherecondition AND mnethostid = :localmnet";
        $order               = ' ORDER BY lastname ASC, firstname ASC';
        $params['localmnet'] = $CFG->mnet_localhost_id;

        // Check to see if there are too many to show sensibly.
        if (!$this->is_validating()) {
            $potentialcount = $DB->count_records_sql($countfields . $sql, $params);
            if ($potentialcount > 100) {
                return $this->too_many_results($search, $potentialcount);
            }
        }

        $availableusers = $DB->get_records_sql($fields . $sql . $order, $params);

        if (empty($availableusers)) {
            return [];
        }

        if ($search) {
            $groupname = get_string('potusersmatching', 'role', $search);
        } else {
            $groupname = get_string('potusers', 'role');
        }

        return [$groupname => $availableusers];
    }

    /**
     * Returns an options array.
     *
     * @return array()
     */
    protected function get_options() {
        global $CFG;
        $options         = parent::get_options();
        $options['file'] = $CFG->admin . '/roles/lib.php';

        return $options;
    }
}
