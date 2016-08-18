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

/**
 * A form to enter in details for saving a QR link.
 *
 * @copyright  2016 Nicholas Hoobin <nicholashoobin@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qrlinks_form extends moodleform {

    /**
     * {@inheritDoc}
     * @see moodleform::definition()
     */
    public function definition() {
        $mform = $this->_form;

        // Public fields.
        $mform->addElement('header', 'qrlink_public_header', get_string('formelementpublicheader', 'local_qrlinks'), '');
        $mform->addElement('static', 'public_help', '', get_string('formelementpublichelp', 'local_qrlinks'));

        $mform->addElement('text', 'url', get_string('formelementurl', 'local_qrlinks'), 'size="50"');
        $mform->setType('url', PARAM_URL);
        $mform->addRule('url', get_string('urlmissing', 'local_qrlinks'), 'required', null, 'server');

        $mform->addElement('text', 'public_name', get_string('formelementpublicname', 'local_qrlinks'), 'size="50"');
        $mform->setType('public_name', PARAM_TEXT);
        $mform->addRule('public_name', get_string('publicnamemissing', 'local_qrlinks'), 'required', null, 'server');

        $mform->addElement('editor', 'public_description', get_string('formelementpublicdescription', 'local_qrlinks'), '');
        $mform->setType('public_description', PARAM_RAW);
        $mform->addRule('public_description', get_string('publicdescriptionmissing', 'local_qrlinks'), 'required', null, 'server');

        // Private fields.
        $mform->addElement('header', 'qrlink_private_header', get_string('formelementprivateheader', 'local_qrlinks'), '');

        $mform->addElement('static', 'private_help', '', get_string('formelementprivatehelp', 'local_qrlinks'));
        $mform->addElement('text', 'private_name', get_string('formelementprivatename', 'local_qrlinks'), 'size="50"');
        $mform->setType('private_name', PARAM_TEXT);

        $mform->addElement('editor', 'private_description', get_string('formelementprivatedescription', 'local_qrlinks'), '');
        $mform->setType('private_description', PARAM_TEXT);

        // Hidden id field.
        $mform->addElement('hidden', 'id', -1);
        $mform->setType('id', PARAM_INT);

        $submitlabel = get_string('createlabel', 'local_qrlinks');
        $this->add_action_buttons(true, $submitlabel);
    }

    /**
     * Returns an array with fields that are invalid while creating a new QR link.
     *
     * @param array $data array of ("fieldname"=>value) of submitted data
     * @param array $files array of uploaded files "element_name"=>tmp_file_path
     * @return array of "element_name"=>"error_description" if there are errors,
     *         or an empty array if everything is OK (true allowed for backwards compatibility too).
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        if ($data['url']) {
            $url = filter_var($data['url'], FILTER_SANITIZE_URL);

            if (filter_var($url, FILTER_VALIDATE_URL) === false) {
                $errors['url'] = get_string('invalidurl', 'local_qrlinks');
            }
        }

        return $errors;
    }

    /**
     * Overrides set_data to populate the editor fields.
     * {@inheritDoc}
     * @see moodleform::set_data()
     * @param stdClass|array $data object or array of default values
     */
    public function set_data($data) {

        $privatedescription = $this->_form->getElement('private_description')->getValue();
        $publicdescription = $this->_form->getElement('public_description')->getValue();

        if (!empty($data->private_description)) {
            $privatedescription['text'] = $data->private_description;

            $data->private_description = array();
            $data->private_description['text'] = $privatedescription['text'];
        }

        if (!empty($data->public_description)) {
            $publicdescription['text'] = $data->public_description;

            $data->public_description = array();
            $data->public_description['text'] = $publicdescription['text'];
        }

        parent::set_data($data);
    }
}
