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
 * Delete a form.
 *
 * @copyright Conn Warwicker <conn@cmrwarwicker.com>
 * @package   local_dataforge
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

// Must be logged in.
require_login();

$id = required_param('id', PARAM_INT);
$confirm = optional_param('confirm', 0, PARAM_INT);

// Form ID must be specified and valid.
$form = \local_dataforge\form::load_from_id($id);

// Must be able to configure forms in this context.
$context = context::instance_by_id($form->contextid);
require_capability('local/dataforge:configure', $context);

// Are we confirming deletion?
if ($confirm) {
    require_sesskey();
    $form->delete();
    redirect(new moodle_url('/local/dataforge/manage.php'), get_string('deleted:form', 'local_dataforge', $form->name, null, \core\output\notification::NOTIFY_SUCCESS));
}

// Setup the page.
$pagetitle = get_string('deleteform', 'local_dataforge', $form->name);
$url = new moodle_url('/local/dataforge/delete.php', [
    'id' => $id,
]);
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title($pagetitle);

// Get the renderer.
$output = $PAGE->get_renderer('local_dataforge');

// Output the page.
echo $output->header();
echo $output->heading($pagetitle);

$renderable = new \local_dataforge\output\delete_form($form, $url);
echo $output->render($renderable);

echo $output->footer();