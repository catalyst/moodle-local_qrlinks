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
 * @return boolean|number return value for the update
 */
function update_qrlink($data) {
    global $DB;

    $ret = $DB->update_record('local_qrlinks', $data);
    return $ret;

}

/**
 * Helper function to delete a QR link record.
 * @param int $id
 * @return boolean|number return value for the delete
 */
function delete_qrlink($id) {
    global $DB;

    $ret = $DB->delete_records('local_qrlinks', array('id' => $id));
    return $ret;
}
