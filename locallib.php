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
 * QR links local library.
 *
 * @package    local_qrlinks
 * @copyright  2016 Nicholas Hoobin <nicholashoobin@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); // It must be included from a Moodle page.
}

require_once($CFG->libdir . '/tablelib.php');

/**
 * Helper function to insert a QR link record.
 * @param stdClass $data
 * @return boolean|number return value for the insert
 */
function insert_qrlink($data) {
    global $DB;

    $ret = $DB->insert_record('local_qrlinks', $data);
    return $ret;
}

/**
 * Helper function to update a QR link record.
 * @param stdClass $data
 * @return boolean return result of the update
 */
function update_qrlink($data) {
    global $DB;

    $res = $DB->update_record('local_qrlinks', $data);
    return $res;
}

/**
 * Helper function to delete a QR link record.
 * @param int $id
 * @return boolean return result of the delete
 */
function delete_qrlink($id) {
    global $DB;

    $res = $DB->delete_records('local_qrlinks', array('id' => $id));
    return $res;
}

/**
 */
function generate_table($cid = null, $cmid = null) {
    global $DB, $PAGE;
    $headers = array(
            get_string('table_header_private_name',         'local_qrlinks'),
            get_string('table_header_private_description',  'local_qrlinks'),
            get_string('table_header_url',                  'local_qrlinks'),
            get_string('table_header_createdby',            'local_qrlinks'),
            get_string('table_header_datecreated',          'local_qrlinks'),
            get_string('table_header_options',              'local_qrlinks'),
    );

    // Used for specifying the max pagination size.
    $qrlinkscount = $DB->count_records('local_qrlinks');

    $columns = array('private_name', 'private_description', 'url', 'createdby', 'timestamp', 'options');

    $table = new flexible_table('local_qrlinks_table');
    $table->define_columns($columns);
    $table->define_headers($headers);
    $table->define_baseurl($PAGE->url);
    $table->set_attribute('id', 'qrlinkst');
    $table->set_attribute('class', 'generaltable admintable');
    $table->pageable(true);
    $table->pagesize(10, $qrlinkscount);
    $table->sortable(true);
    $table->no_sorting('options');
    $table->setup();

    return $table;
}

function generate_table_data($table, $cid = null) {
    global $DB;

    $where = '';
    $params = array();

    if ($cid > -1) {
        $where = 'WHERE q.courseid = ?';
        $params = array('cid' => $cid);
    }

    $orderby = $table->get_sql_sort();

    $query = "SELECT q.id,
                     q.public_name,
                     q.public_description,
                     q.url,
                     q.createdby,
                     q.timestamp,
                     q.private_name,
                     q.private_description,
                     u.firstname,
                     u.lastname,
                     u.id AS uid
    FROM {local_qrlinks} q
    JOIN {user} u ON u.id = q.createdby
    $where";

    if (!empty($orderby)) {
        $query .= "ORDER BY $orderby";
    }

    $result = $DB->get_records_sql($query, $params, $table->get_page_start(), $table->get_page_size());

    return $result;
}
