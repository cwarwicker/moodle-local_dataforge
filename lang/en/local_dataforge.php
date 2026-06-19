<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Language strings for local_dataforge.
 *
 * @copyright Conn Warwicker <conn@cmrwarwicker.com>
 * @package   local_dataforge
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'DataForge';

$string['actions'] = 'Actions';
$string['confirm:delete:form'] = 'Are you sure you want to delete this form? It has {$a} responses, which will also be deleted.';
$string['createform'] = 'Create new form';
$string['delete'] = 'Delete';
$string['deleted:form'] = 'Form deleted: {$a}';
$string['deleteform'] = 'Delete form: {$a}';
$string['edit'] = 'Edit';
$string['editform'] = 'Edit form: {$a}';
$string['manageforms'] = 'Manage forms';
$string['name'] = 'Name';
$string['newform'] = 'Create new form';
$string['pleasechoose'] = 'Please choose...';
$string['reports'] = 'Reports';
$string['rule:required'] = 'This field is required';
$string['type'] = 'Type';
$string['form:type:report'] = 'Database Report';
$string['form:type:report:description'] = 'A display-only report built from an SQL query on your database, with field mappings to display the data in the way you want.';
$string['form:type:multiple'] = 'Multiple Report (Internal)';
$string['form:type:multiple:description'] = 'A set of form fields which can be filled out multiple times, creating new instances. Example: Course Reports, Comments, etc...';
$string['form:type:single'] = 'Single Report (Internal)';
$string['form:type:single:description'] = 'A single set of form fields which can be filled out once. Responses can be updated, but there will only be one instance of them. Example: Personal information - Date of Birth, Address, etc...';

// Errors.
$string['error:type:invalid'] = 'Invalid form field type ({$a})';