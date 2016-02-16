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
 * QR links library.
 *
 * @package    local_qrlinks
 * @copyright  2016 Nicholas Hoobin <nicholashoobin@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

function local_qrlinks_extend_settings_navigation($settingsnav, $context) {
    global $CFG, $PAGE;

    // Only add this settings item on non-site course pages.
    if(!$PAGE->course or $PAGE->course->id == 1) {
        return;
    }

    // https://docs.moodle.org/dev/Local_plugins
    if ($settingsnode = $settingsnav->find('courseadmin', navigation_node::TYPE_COURSE)) {
        $strfoo = get_string('pluginname', 'local_qrlinks');
        $url = new moodle_url('/local/qrlinks/manage.php', array('cid' => $PAGE->course->id));
        $foonode = navigation_node::create(
                $strfoo,
                $url,
                navigation_node::NODETYPE_LEAF,
                'qrlinks',
                'qrlinks',
                new pix_icon('t/addcontact', $strfoo)
        );

        if ($PAGE->url->compare($url, URL_MATCH_BASE)) {
            $foonode->made_active();
        }

        $settingsnode->add_node($foonode);
    }
}
