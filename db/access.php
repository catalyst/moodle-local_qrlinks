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
 * Access file definitions for plugin local_qrlinks
 *
 * @package    local_qrlinks
 * @copyright  2016 Nicholas Hoobin <nicholashoobin@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$capabilities = array(
    'local/qrlinks:create' => array (
        'captype'       => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
            'guest'          => CAP_PROHIBIT,
            'user'           => CAP_PROHIBIT,
            'student'        => CAP_PROHIBIT,
            'teacher'        => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager'        => CAP_ALLOW
        )
    ),

    'local/qrlinks:delete' => array (
        'captype'       => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
            'guest'          => CAP_PROHIBIT,
            'user'           => CAP_PROHIBIT,
            'student'        => CAP_PROHIBIT,
            'teacher'        => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager'        => CAP_ALLOW
        )
    ),

    'local/qrlinks:view' => array (
        'captype'       => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
            'guest'          => CAP_ALLOW,
            'user'           => CAP_ALLOW,
            'student'        => CAP_ALLOW,
            'teacher'        => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager'        => CAP_ALLOW
        )
    )
);
