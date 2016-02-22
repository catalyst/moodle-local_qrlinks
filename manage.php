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

require_once('qrlinks_table.php');
require_once('locallib.php');

$courseid = optional_param('cid', -1, PARAM_INT);
$moduleid = optional_param('cmid', -1, PARAM_INT);
$delete   = optional_param('delete', 0, PARAM_INT);
$edit     = optional_param('edit', 0, PARAM_INT);
$confirm  = optional_param('confirm', '', PARAM_ALPHANUM);   // MD5 confirmation hash.

$returnurl = new moodle_url('/local/qrlinks/manage.php');

if ($courseid > -1) {
    $context = context_course::instance($courseid);
    $PAGE->set_pagelayout('course');
    $url = new moodle_url('/local/qrlinks/manage.php', array('cid' => $courseid));
    $PAGE->set_url($url);
    $returnurl = $url;

} else if ($moduleid > -1) {
    $context = context_module::instance($moduleid);
    $PAGE->set_pagelayout('module');
    $url = new moodle_url('/local/qrlinks/manage.php', array('cmid' => $moduleid));
    $PAGE->set_url($url);
    $returnurl = $url;

} else {
    $context = context_system::instance();
    $PAGE->set_pagelayout('admin');
    $url = new moodle_url('/local/qrlinks/manage.php');
    $PAGE->set_url($url);
    $returnurl = $url;

}

// admin_externalpage_setup('local_qrlinks', '', null);

require_login();

$PAGE->set_context($context);

if ($delete && confirm_sesskey()) {

    if ($confirm != md5($delete)) {
        require_capability('local/qrlinks:delete', $context);

        $qrlinkname = $DB->get_field('local_qrlinks', 'name', array('id' => $delete));

        echo $OUTPUT->header();
        echo $OUTPUT->heading(get_string('deletelinkheader', 'local_qrlinks'));

        $optionsyes = array('delete' => $delete, 'confirm' => md5($delete), 'sesskey' => sesskey());
        $deleteurl = new moodle_url($returnurl, $optionsyes);
        $deletebutton = new single_button($deleteurl, get_string('delete'), 'post');

        echo $OUTPUT->confirm(get_string('deletelinkdescription', 'local_qrlinks', $qrlinkname), $deletebutton, $returnurl);
        echo $OUTPUT->footer();

        return;

    } else if (data_submitted()) {
        delete_qrlink($delete);

        redirect($returnurl);

    } else {
        redirect($returnurl);
    }
}

$PAGE->set_title(get_string('pluginname', 'local_qrlinks'));
$PAGE->set_heading(get_string('manage_page_heading', 'local_qrlinks'));

echo $OUTPUT->header();

qrlinks_table($courseid, $moduleid);

if (has_capability('local/qrlinks:create', $context)) {
    $url = new moodle_url('/local/qrlinks/qrlinks_edit.php', array('id' => -1));

    echo html_writer::empty_tag('br');
    echo $OUTPUT->single_button($url, get_string('addnewlink', 'local_qrlinks'), 'get');
}

echo $OUTPUT->footer();
