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
        $headtext .= $data->name;
        $headtext .= html_writer::end_tag('h2');
        $headdiv = html_writer::div($headtext, 'qrheader');

        $desctext = $data->description;
        $descdiv = html_writer::div($desctext, 'qrdescription');

        echo $headdiv;

        // Will included an <img src="data:image/png;base64,... /> to be visible.
        include('qr.php');

        echo $descdiv;
    }
}

