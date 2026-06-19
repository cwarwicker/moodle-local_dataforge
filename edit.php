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
 * Edit or create a form.
 *
 * @copyright Conn Warwicker <conn@cmrwarwicker.com>
 * @package   local_dataforge
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

// Must be logged in.
require_login();

$id = optional_param('id', null, PARAM_INT);
$contextid = optional_param('contextid', null, PARAM_INT);
$form = null;
$urlparams = [];

// If we are editing an existing form load it.
if ($id) {
    $form = \local_dataforge\form::load_from_id($id);
    $context = context::instance_by_id($form->contextid);
    $urlparams['id'] = $id;
} else if ($contextid) {
    // Instead, if we are creating a new form, we should have supplied a context ID in which to do so.
    $context = context::instance_by_id($contextid);
    $urlparams['contextid'] = $contextid;
} else {
    // If we didn't, fallback to system.
    $context = \core\context\system::instance();
}

// Must be able to configure forms in this context.
require_capability('local/dataforge:configure', $context);

// Setup the page.
$pagetitle = ($form) ?
    get_string('editform', 'local_dataforge', $form->name) :
    get_string('createform', 'local_dataforge');

$url = new moodle_url('/local/dataforge/edit.php', $urlparams);
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title($pagetitle);

// Get the renderer.
$output = $PAGE->get_renderer('local_dataforge');

// Get the actual moodle form for creating the data form.
$mform = new \local_dataforge\forms\edit_form(null, ['context' => $context, 'form' => $form]);

// Output the page.
echo $output->header();
echo $output->heading($pagetitle);

echo $mform->render();

echo $output->footer();