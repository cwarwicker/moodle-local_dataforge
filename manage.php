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
 * Manage forms.
 *
 * @copyright Conn Warwicker <conn@cmrwarwicker.com>
 * @package   local_dataforge
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

// Must be logged in.
require_login();

// Forms can be created in different contexts. If not set, we default to system.
$contextid = optional_param('contextid', \core\context\system::instance()->id, PARAM_INT);
$context = context::instance_by_id($contextid);

// Must have the configure capability in this context.
require_capability('local/dataforge:configure', $context);

// Get the existing forms in this context.

// testing
$form = \local_dataforge\form::find(['id' => 2]);
dd($form->delete());