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
 * QR link incoming logic page.
 *
 * @package    local_qrlinks
 * @copyright  2016 Nicholas Hoobin <nicholashoobin@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->libdir . '/accesslib.php');
global $DB;

$id = required_param('id', PARAM_INT);

// Guest login from moodlelib.php line 2546.
if (!isloggedin()) {
    if (!$guest = get_complete_user_data('id', $CFG->siteguest)) {
        // Misconfigured site guest, just redirect to login page.
        redirect(get_login_url());
        exit; // Never reached.
    }

    $lang = isset($SESSION->lang) ? $SESSION->lang : $CFG->lang;
    complete_user_login($guest);
    $SESSION->lang = $lang;
}

$data = $DB->get_record('local_qrlinks', array('id' => $id));

$url = $data->url;

redirect($url);
