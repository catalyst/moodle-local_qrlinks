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
 * Render and display a QR link.
 *
 * @package    local_qrlinks
 * @copyright  2016 Nicholas Hoobin <nicholashoobin@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

header('Content-Type: image/png');

use Endroid\QrCode\QrCode;
require_once("thirdparty/QrCode/src/QrCode.php");

$qrid = required_param('id', PARAM_INT);
$data = new moodle_url('/local/qrlinks/in.php', array('id' => $qrid));

$code = new QrCode();
$code->setText($data);
$code->setSize(300);
$code->setPadding(6);
$code->setErrorCorrection('high');
$code->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0));
$code->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0));
$code->setLabelFontSize(16);
$code->render();
