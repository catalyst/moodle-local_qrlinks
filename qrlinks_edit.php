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
 * Allows you to edit a QR link.
 *
 * @package    local_qrlinks
 * @copyright  2016 Nicholas Hoobin <nicholashoobin@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

require_once('qrlinks_form.php');
require_once('locallib.php');

$id       = optional_param('id', -1, PARAM_INT);
$courseid = optional_param('cid', -1, PARAM_INT);
$moduleid = optional_param('cmid', -1, PARAM_INT);

// Cleaning up the URL, not adding parameters to the end of the address if the page is called by itself.
if($id > -1) {
    $PAGE->set_url(new moodle_url('/local/qrlinks/qrlinks_edit.php', array('id' => $id)));
} else {
    $PAGE->set_url(new moodle_url('/local/qrlinks/qrlinks_edit.php'));
}

$sitecontext = context_system::instance();
$PAGE->set_context($sitecontext);
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'local_qrlinks'));
$PAGE->set_heading(get_string('manage_page_heading', 'local_qrlinks'));

/*
$array = array();
if ($courseid > -1) {
    $array = array('cid' => $courseid);
} else if ($moduleid > -1) {
    $array = array('cid' => $courseid, 'cmid' => $moduleid);
}
*/

$refererurl = $_SERVER['HTTP_REFERER'];
$returnurl = new moodle_url('/local/qrlinks/manage.php');

$mform = new qrlinks_form();

if ($mform->is_cancelled()) {
    redirect($returnurl);

} else if ($fromform = $mform->get_data()) {
    $qrid = $fromform->id;
    $name = $fromform->name;
    $description = $fromform->description;
    $url = $fromform->url;

    $data = array(
            'id' => $qrid,
            'name' => $name,
            'description' => $description,
            'url' => $url,
            'createdby' => $USER->id,
            'timestamp' => time()
    );

    // Update the QR link with a known ID
    if ($qrid > -1) {
        update_qrlink($data);

    // Insert a QR link when the ID is unset or -1.
    } else {
        insert_qrlink($data);
    }

    redirect($returnurl);
}

// The data will be populated from an existing record.
if ($id > -1) {
    $data = $DB->get_record('local_qrlinks', array('id' => $id));
    $mform->set_data($data);
} else {

    // Do not populate the URL field if we just arrived from the manage.php page.
    $re = "/local\\/qrlinks\\/manage\\.php/";
    if(!preg_match($re, $refererurl, $matches)) {
        $data['url'] = $refererurl;
        $mform->set_data($data);
    }
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
