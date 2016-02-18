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
 * Manage QR links.
 *
 * @package    local_qrlinks
 * @copyright  2016 Nicholas Hoobin <nicholashoobin@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');

require_once('qrlinks_form.php');
require_once('qrlinks_table.php');

$courseid = optional_param('cid', -1, PARAM_INT);
$moduleid = optional_param('cmid', -1, PARAM_INT);

admin_externalpage_setup('local_qrlinks', '', null);

//require_login();

$PAGE->set_context(context_system::instance());

$PAGE->set_url(new moodle_url('/local/qrlinks/manage.php'), array('cid' => $courseid, 'cmid' => $moduleid));

$PAGE->set_pagelayout('admin');
$PAGE->set_title('Basic page');
$PAGE->set_heading('Heading');
// $PAGE->navbar->add('QR Links management', new moodle_url('management.php'));

echo $OUTPUT->header();

# https://docs.moodle.org/dev/Form_API
$mform = new qrlinks_form();
if ($mform->is_cancelled()) {

} else if ($fromform = $mform->get_data()) {
    $name = $fromform->name;
    $description = $fromform->description;
    $url = $fromform->url;

    $data = array('name' => $name,
            'description' => $description,
            'url' => $url,
            'createdby' => $USER->id,
            'timestamp' => time());

    echo '<pre>' . print_r($data, 1) . '</pre>';

    $DB->insert_record('local_qrlinks', $data);

} else {
    //$renderer = $PAGE->get_renderer('local_qrlinks');
    //echo $renderer->render_qrlinks_list($data);
    qrlinks_table();

    $mform->display();

    $data = $DB->get_records('local_qrlinks');
    echo '<pre>' . print_r($data, 1) . '</pre>';
}

echo $OUTPUT->footer();
