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

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); // It must be included from a Moodle page.
}

/**
 * Extends the navigation block and adds a new node 'Create QR link'. This is visible if the current
 * user has the local/qrlinks:create capability.
 *
 * If the current page has an existing QR link it will prompt to edit the link.
 * Or if the current page is editing a QR link it will prompt to manage the total list of QR links.
 *
 * @param global_navigation $nav
 */
function local_qrlinks_extend_navigation(global_navigation $nav) {
    global $PAGE, $DB;

    if (has_capability('local/qrlinks:create', $PAGE->context)) {
        $currentpage = $PAGE->url->out();

        $qrlinkre = "/local\\/qrlinks\\/(manage|qrlinks_edit)\\.php/";
        $indexre = "/local\\/qrlinks\\/(index)\\.php/";

        if (preg_match($qrlinkre, $currentpage, $matches)) {
            // We are in the edit/management page, lets show a link back to management.
            $str = get_string('manage_link', 'local_qrlinks');
            $url = new moodle_url('/local/qrlinks/manage.php');
            $linknode = $nav->add($str, $url);

        } else if (preg_match($indexre, $currentpage, $matches)) {
            // We are looking at the QR link preview / helper page. Linking to edit.

            // The qrlinks/index.php has a required parameter 'id'.
            $id = required_param('id', PARAM_INT);

            $params = array('id' => $id);

            $str = get_string('navigation_edit_link', 'local_qrlinks');
            $url = new moodle_url('/local/qrlinks/qrlinks_edit.php', $params);
            $linknode = $nav->add($str, $url);

        } else {
            // We are looking at any other page, lets query the database and check against the url to see if a QR has been made already.
            $compareclause = $DB->sql_compare_text('url') . ' = ' . $DB->sql_compare_text(':url');

            $params = array('url' => $currentpage);

            $query = "SELECT id, url
                        FROM {local_qrlinks}
                       WHERE $compareclause";

            // If this is true, then a QR link has already been assigned to this page.
            $qrlink = $DB->get_record_sql($query, $params, IGNORE_MULTIPLE);
            // TODO: If more than one link found, have intermediate page showing results and selection to edit.

            if (empty($qrlink)) {
                // No QR link found, lets make a Create Link.
                $str = get_string('navigation_create_link', 'local_qrlinks');
                $url = new moodle_url('/local/qrlinks/qrlinks_edit.php');
                $linknode = $nav->add($str, $url);

            } else {
                // QR link found, time for an Edit link.
                $id = $qrlink->id;
                $str = get_string('navigation_edit_link', 'local_qrlinks');
                $url = new moodle_url('/local/qrlinks/qrlinks_edit.php', array('id' => $id));
                $linknode = $nav->add($str, $url);

            }
        }
    }
}

/**
 * Extends the settings navigation and adds a new node 'Create QR link'. This is visible if the current
 * user has the local/qrlinks:create capability.
 *
 * @param settings_navigation $nav
 * @param context $context
 */
function local_qrlinks_extend_settings_navigation(settings_navigation $nav, context $context) {
    global $PAGE;

    if (has_capability('local/qrlinks:create', $PAGE->context)) {

        $courseid = $PAGE->course->id;
        $linkparams = array('cid' => $courseid);

        if ($PAGE->cm) {
            $module = $PAGE->cm->id;
            $linkparams = array('cid' => $courseid, 'cmid' => $module);
        }

        // Only add this settings item on non-site course pages.
        if (!$PAGE->course or $courseid == 1) {
            return;
        }

        // https://docs.moodle.org/dev/Local_plugins
        if ($settingsnode = $nav->find('courseadmin', navigation_node::TYPE_COURSE)) {
            $str = get_string('manage_link', 'local_qrlinks');
            $url = new moodle_url('/local/qrlinks/manage.php', $linkparams);
            $node = navigation_node::create(
                    $str,
                    $url,
                    navigation_node::NODETYPE_LEAF,
                    'qrlinks',
                    'qrlinks',
                    new pix_icon('t/edit', $str)
            );

            if ($PAGE->url->compare($url, URL_MATCH_BASE)) {
                $node->make_active();
            }

            $settingsnode->add_node($node);
        }
    }
}

