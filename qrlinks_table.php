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
 * QR links table_sql class.
 *
 * @package    local_qrlinks
 * @copyright  2016 Nicholas Hoobin <nicholashoobin@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); // It must be included from a Moodle page.
}

require_once($CFG->libdir . '/tablelib.php');

function qrlinks_table($cid = null, $cmid = null) {
    global $DB, $PAGE, $OUTPUT;

    $stredit   = get_string('edit');
    $strdelete = get_string('delete');

    $headers = array(get_string('table_header_name', 'local_qrlinks'),
            get_string('table_header_description', 'local_qrlinks'),
            get_string('table_header_url', 'local_qrlinks'),
            get_string('table_header_createdby', 'local_qrlinks'),
            get_string('table_header_datecreated', 'local_qrlinks'),
            get_string('table_header_options', 'local_qrlinks')
    );

    $columns = array('name', 'description', 'url', 'createdby', 'timestamp', 'options');

    // Used for specifying the max pagination size.
    $qrlinkscount = $DB->count_records('local_qrlinks');

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

    list($where, $params) = $table->get_sql_where();
    $orderby = $table->get_sql_sort();

    $query = "SELECT q.id, q.name, q.description, q.url, q.createdby, q.timestamp,
                     u.firstname, u.lastname, u.id AS uid
                FROM {local_qrlinks} q
                JOIN {user} u ON u.id = q.createdby
                $where
                ORDER BY $orderby";

    $result = $DB->get_records_sql($query, $params, $table->get_page_start(), $table->get_page_size());

    if (!empty($result)) {
        foreach ($result as $entry) {
            $buttons = array();

            $cmidarray = array();
            if ($cid > -1) {
                $cmidarray = array('cid' => $cid);
            } else if ($cmid > -1) {
                $cmidarray = array('cmid' => $cmid);
            }

            if (has_capability('local/qrlinks:delete', context_system::instance())) {
                $url = new moodle_url('', array_merge(array('delete' => $entry->id, 'sesskey' => sesskey()), $cmidarray));
                $buttons[] = html_writer::link($url, html_writer::empty_tag('img', array('src' => $OUTPUT->pix_url('t/delete'), 'alt' => $strdelete, 'class' => 'iconsmall')), array('title' => $strdelete));
            }

            if (has_capability('local/qrlinks:create', context_system::instance())) {
                $url = new moodle_url('/local/qrlinks/qrlinks_edit.php', array_merge(array('id' => $entry->id, 'sesskey' => sesskey()), $cmidarray));
                $buttons[] = html_writer::link($url, html_writer::empty_tag('img', array('src' => $OUTPUT->pix_url('t/edit'), 'alt' => $stredit, 'class' => 'iconsmall')), array('title' => $stredit));
            }

            $id = $entry->id;
            $name = $entry->name;
            $description = $entry->description;
            $url = $entry->url;
            $createdby = $entry->firstname . " " . $entry->lastname;
            $timestamp = $entry->timestamp;
            $options = implode(' ', $buttons);

            $values = array($name, $description, $url, $createdby, $timestamp, $options);
            $table->add_data($values);
        }
    }

    $output = $table->finish_output();
    return $output;
}