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
 * QR links editing form
 *
 * @package    local_qrlinks
 * @copyright  2016 Nicholas Hoobin <nicholashoobin@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); // It must be included from a Moodle page.
}

require_once($CFG->libdir . '/formslib.php');

class qrlinks_form extends moodleform {
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('header', 'qrlink_header', get_string('form_element_header', 'local_qrlinks'), '');

        $mform->addElement('text', 'name', get_string('form_element_name', 'local_qrlinks'), 'size="50"');
        $mform->setType('name', PARAM_TEXT);

        $mform->addElement('text', 'url', get_string('form_element_url', 'local_qrlinks'), 'size="50"');
        $mform->setType('url', PARAM_URL);

        $mform->addElement('textarea', 'description', get_string('form_element_description', 'local_qrlinks'), 'cols="50"');
        $mform->setType('description', PARAM_TEXT);

        $mform->addElement('hidden', 'id', -1);
        $mform->setType('id', PARAM_INT);

        $submitlabel = get_string('previewlabel', 'local_qrlinks');

        $this->add_action_buttons(true, $submitlabel);

    }

    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        $url = filter_var($data['url'], FILTER_SANITIZE_URL);

        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            $errors['url'] = get_string('invalidurl', 'local_qrlinks');
        }

        return $errors;
    }
}
