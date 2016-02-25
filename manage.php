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

require_once('renderer.php');
require_once('locallib.php');

$courseid = optional_param('cid', null, PARAM_INT);
$moduleid = optional_param('cmid', null, PARAM_INT);
$delete   = optional_param('delete', 0, PARAM_INT);
$confirm  = optional_param('confirm', '', PARAM_ALPHANUM);   // MD5 confirmation hash.

$returnurl = new moodle_url('/local/qrlinks/manage.php');

if ($moduleid) {
    require_login($courseid, false, $moduleid);

    $context = context_module::instance($moduleid);
    $url = new moodle_url('/local/qrlinks/manage.php', array('cmid' => $moduleid));
    $PAGE->set_url($url);
    $PAGE->set_pagelayout('incourse');
    $returnurl = $url;

} else if ($courseid) {
    require_login($courseid);

    $context = context_course::instance($courseid);
    $url = new moodle_url('/local/qrlinks/manage.php', array('cid' => $courseid));
    $PAGE->set_url($url);
    $PAGE->set_pagelayout('incourse');
    $returnurl = $url;
} else {
    require_login();

    $context = context_system::instance();
    $PAGE->set_pagelayout('admin');
    $url = new moodle_url('/local/qrlinks/manage.php');
    $PAGE->set_url($url);
    $returnurl = $url;
}

$PAGE->set_context($context);

require_capability('local/qrlinks:manage', $context);

if ($delete && confirm_sesskey()) {

    if ($confirm != md5($delete)) {
        $query = "SELECT private_name,
                         url
                    FROM {local_qrlinks}
                   WHERE id = ?";

        $params = array('id' => $delete);

        $result = $DB->get_record_sql($query, $params);

        echo $OUTPUT->header();
        echo $OUTPUT->heading(get_string('delete_link_header', 'local_qrlinks'));

        $optionsyes = array('delete' => $delete, 'confirm' => md5($delete), 'sesskey' => sesskey());
        $deleteurl = new moodle_url($returnurl, $optionsyes);
        $deletebutton = new single_button($deleteurl, get_string('delete'), 'post');

        echo $OUTPUT->confirm(get_string('delete_link_description', 'local_qrlinks', $result->url), $deletebutton, $returnurl);
        echo $OUTPUT->footer();

        exit;

    } else if (data_submitted()) {
        delete_qrlink($delete);

        redirect($returnurl);

    } else {
        redirect($returnurl);
    }
}

$PAGE->set_title(get_string('pluginname', 'local_qrlinks'));
$PAGE->set_heading(get_string('manage_page_heading', 'local_qrlinks'));
$renderer = $PAGE->get_renderer('local_qrlinks');

echo $OUTPUT->header();

echo $renderer->render_qrlinks_qrlinks_table($courseid, $moduleid);

$url = new moodle_url('/local/qrlinks/qrlinks_edit.php');

echo html_writer::empty_tag('br');
echo $OUTPUT->single_button($url, get_string('add_new_link', 'local_qrlinks'), 'get');

echo $OUTPUT->footer();
