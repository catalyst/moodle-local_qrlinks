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
 * Strings for component 'local_qrlinks', language 'en'.
 *
 * @package    local_qrlinks
 * @copyright  2016 Nicholas Hoobin <nicholashoobin@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// General plugin strings.
$string['pluginname'] = 'QR Links';
$string['create_label'] = 'Create QR link';

// Strings for index.php.
$string['preview_label'] = 'Preview QR link';
$string['preview_title'] = 'QR link: {$a}';

// Strings for lib.php.
$string['manage_link'] = 'Manage QR links';
$string['navigation_create_link'] = 'Create QR link';
$string['navigation_edit_link'] = 'Edit QR link';
$string['navigation_edit_links'] = 'Edit QR links ({$a})';

// Strings for manage.php.
$string['delete_link_header'] = 'Delete QR Link';
$string['delete_link_description'] = 'Are you absolutely sure you want to completely delete the QR Link \'{$a}\'?';
$string['manage_page_heading'] = 'QR Links Management';
$string['add_new_link'] = 'Add new QR Link';

// Strings for qrlinks_edit.php.
// pluginname
// manage_page_heading

// Strings for qrlinks_form.php
// create_label
$string['form_element_header'] = 'Generate QR link';

$string['form_element_public_header'] = 'Public fields';
$string['form_element_public_help'] = 'These fields will be viewable on the public helper page';
$string['form_element_public_name'] = 'Public Name';
$string['form_element_public_description'] = 'Public Description';

$string['public_name_missing'] = 'Please provide a name for this QR link';
$string['public_description_missing'] = 'Please provide a description for this QR link';

$string['form_element_url'] = 'URL';
$string['url_missing'] = 'Please provide a URL for this QR link';
$string['invalid_url'] = 'Invalid URL';

$string['form_element_private_header'] = 'Private fields';
$string['form_element_private_help'] = 'These fields are private identifiers for administering QR links';
$string['form_element_private_name'] = 'Private Name';
$string['form_element_private_description'] = 'Private Description';

// Table strings.
$string['table_header_private_name'] = 'Private Name';
$string['table_header_private_description'] = 'Private Description';
$string['table_header_public_name'] = 'Public Name';
$string['table_header_public_description'] = 'Public Description';
$string['table_header_url'] = 'URL';
$string['table_header_createdby'] = 'User';
$string['table_header_datecreated'] = 'Date';
$string['table_header_options'] = 'Options';
$string['table_preview'] = 'Preview';

// Strings for capabilities.
$string['qrlinks:manage'] = 'Manage QR links';
$string['qrlinks:view'] = 'View a QR link';
