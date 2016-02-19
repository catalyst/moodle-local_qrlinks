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
    die('Direct access to this script is forbidden.'); // It must be included from a Moodle page
}

class local_qrlinks_renderer extends plugin_renderer_base {
    public function render_qrlinks_list($list) {
        $table = new html_table();

        $table->head = array('Name', 'Description', 'Url', 'Options');

        foreach ($list as $qr) {
            $row = new html_table_row();

            $namecell = new html_table_cell($qr->name);
            $descriptioncell = new html_table_cell($qr->description);
            $urlcell = new html_table_cell($qr->url);

            $row->cells = array($namecell, $descriptioncell, $urlcell);

            $table->data[] = $row;
        }

        $out = html_writer::table($table);
        return $out;
    }

}

