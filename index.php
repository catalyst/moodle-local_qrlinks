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
 * QR link helper page to provide description and urls.
 *
 * @package    local_qrlinks
 * @copyright  2016 Nicholas Hoobin <nicholashoobin@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

$id = required_param('id', PARAM_INT);

$PAGE->set_url(new moodle_url('/local/qrlinks/index.php', array('id' => $id)));

$sitecontext = context_system::instance();
$PAGE->set_context($sitecontext);
$PAGE->set_pagelayout('embedded');

$PAGE->set_title(get_string('pluginname', 'local_qrlinks'));
$PAGE->set_heading(get_string('manage_page_heading', 'local_qrlinks'));

$data = $DB->get_record('local_qrlinks', array('id' => $id));


echo $OUTPUT->header();

print_object($data);
require_once('qrlink.php');

echo $OUTPUT->footer();
