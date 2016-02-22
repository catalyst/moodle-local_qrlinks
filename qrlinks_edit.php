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

$sitecontext = context_system::instance();

$PAGE->set_context($sitecontext);
$PAGE->set_url(new moodle_url('/local/qrlinks/qrlinks_edit.php', array('id' => $id)));
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'local_qrlinks'));
$PAGE->set_heading(get_string('manage_page_heading', 'local_qrlinks'));

$array = array();
if ($courseid > -1) {
    $array = array('cid' => $courseid);
} else if ($moduleid > -1) {
    $array = array('cmid' => $moduleid);
}

$returnurl = new moodle_url('/local/qrlinks/manage.php', $array);

$mform = new qrlinks_form();

if ($mform->is_cancelled()) {
    redirect($returnurl);

} else if ($fromform = $mform->get_data()) {
    $name = $fromform->name;
    $description = $fromform->description;
    $url = $fromform->url;

    $data = array('name' => $name,
            'description' => $description,
            'url' => $url,
            'createdby' => $USER->id,
            'timestamp' => time());

    insert_qrlink($data);

    redirect($returnurl);
}

if ($id != -1) {
    $data = $DB->get_record('local_qrlinks', array('id' => $id));
    $mform->set_data($data);
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
