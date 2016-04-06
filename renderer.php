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
 * QR links renderer.
 *
 * @package    local_qrlinks
 * @copyright  2016 Nicholas Hoobin <nicholashoobin@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); // It must be included from a Moodle page.
}

/**
 * Renderer for QR links.
 *
 * @copyright  2016 Nicholas Hoobin (nicholashoobin@catalyst-au.net)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class local_qrlinks_renderer extends plugin_renderer_base {

    /**
     * Render the QR helper block.
     * @param array $data
     */
    public function render_qrlinks_helper($data) {
        $headtext  = html_writer::start_tag('h2');
        $headtext .= $data->public_name;
        $headtext .= html_writer::end_tag('h2');
        $headdiv = html_writer::div($headtext, 'qrheader');

        $desctext = $data->public_description;
        $descdiv = html_writer::div($desctext, 'qrdescription');

        $qrurl = new moodle_url("/local/qrlinks/qr.php?id");
        $alt = $data->url;
        $urlstr = html_writer::link($data->url, $data->url);

        $out  = $headdiv;
        $out .= html_writer::img($qrurl, $alt);
        $out .= html_writer::empty_tag('br');
        $out .= $urlstr;
        $out .= $descdiv;

        return $out;
    }

    /**
     * Renders a helpful button to print the page.
     */
    public function render_qrlinks_print_button() {
        return '<button id="printbutton" onclick="window.print();">' . get_string('print_page', 'local_qrlinks') . '</button>';
    }

    /**
     * Renders a table using tablelib.php to show a list of QR codes that have been generated.
     *
     * @param int $cid course id
     * @param int $cmid course module id
     */
    public function render_qrlinks_qrlinks_table($table, $data, $cid, $cmid) {
        global $OUTPUT;

        $stredit    = get_string('edit');
        $strdelete  = get_string('delete');
        $strpreview = get_string('table_preview', 'local_qrlinks');

        if (!empty($data)) {
            foreach ($data as $entry) {
                $buttons = array();

                $cmidarray = array();
                if ($cid > -1) {
                    $cmidarray = array('cid' => $cid);
                } else if ($cmid > -1) {
                    $cmidarray = array('cmid' => $cmid);
                }

                // Delete button.
                if (has_capability('local/qrlinks:manage', context_system::instance())) {
                    $url = new moodle_url('', array_merge(array('delete' => $entry->id, 'sesskey' => sesskey()), $cmidarray));
                    $html = html_writer::empty_tag('img', array('src' => $OUTPUT->pix_url('i/delete'), 'alt' => $strdelete, 'class' => 'iconsmall'));
                    $buttons[] = html_writer::link($url, $html, array('title' => $strdelete));
                }

                // Preview button for viewing the generated QR link information page.
                $url = new moodle_url('/local/qrlinks/index.php', array('id' => $entry->id));
                $html = html_writer::empty_tag('img', array('src' => $OUTPUT->pix_url('i/preview'), 'alt' => $strpreview, 'class' => 'iconsmall'));
                $buttons[] = html_writer::link($url, $html, array('title' => $strpreview));

                // Edit button.
                if (has_capability('local/qrlinks:manage', context_system::instance())) {
                    $url = new moodle_url('/local/qrlinks/qrlinks_edit.php', array_merge(array('id' => $entry->id, 'sesskey' => sesskey()), $cmidarray));
                    $html = html_writer::empty_tag('img', array('src' => $OUTPUT->pix_url('i/edit'), 'alt' => $stredit, 'class' => 'iconsmall'));
                    $buttons[] = html_writer::link($url, $html, array('title' => $stredit));
                }

                $id = $entry->id;
                $privatename = $entry->private_name;
                $privatedescription = strip_tags($entry->private_description);
                $url = html_writer::link($entry->url, $entry->url);
                $createdby = $entry->firstname . " " . $entry->lastname;
                $timestamp = userdate($entry->timestamp, '%Y/%m/%d');
                $options = implode(' ', $buttons);

                $values = array($privatename, $privatedescription, $url, $createdby, $timestamp, $options);
                $table->add_data($values);
            }
        }

        $output = $table->finish_output();
        return $output;
    }
}
