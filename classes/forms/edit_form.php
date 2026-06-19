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
 * Edit/Create a form.
 *
 * This is the Moodle form class for it.
 *
 * @copyright Conn Warwicker <conn@cmrwarwicker.com>
 * @package   local_dataforge
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dataforge\forms;

use core\output\choicelist;
use local_dataforge\form;

require_once($CFG->dirroot.'/lib/formslib.php');

class edit_form extends \moodleform {
    /**
     * Define form elements.
     *
     */
    protected function definition(): void {
        global $PAGE;

        // Name of the form.
        $this->_form->addElement('text', 'name', get_string('name', 'local_dataforge'));
        $this->_form->setType('name', PARAM_TEXT);
        $this->_form->addRule('name', get_string('rule:required', 'local_dataforge'), 'required');

        // Type of form.
        $choices = new choicelist();
        foreach (form::TYPES as $type) {
            $choices->add_option(
                $type,
                get_string('form:type:' . $type, 'local_dataforge'),
                [
                    'description' => get_string('form:type:' . $type . ':description', 'local_dataforge'),
                ]
            );
        }
        $this->_form->addElement('choicedropdown', 'type', get_string('type', 'local_dataforge'), $choices);
        $this->_form->setType('type', PARAM_TEXT);
        $this->_form->addRule('type', get_string('rule:required', 'local_dataforge'), 'required');

        // Context of the form. For reference.
        $this->_form->addElement(
            'static',
            'context',
            get_string('context', 'core'),
            $this->_customdata['context']->get_context_name(),
        );

        // Hidden field for all the field data which will be stored in JSON and then processed.
        $this->_form->addElement('hidden', 'fields');
        $this->_form->setType('fields', PARAM_RAW);

        // Then an HTML field for all the HTML and JS we need to make the field editing work.
        $output = $PAGE->get_renderer('local_dataforge');
        $renderable = new \local_dataforge\output\edit_fields();
        $this->_form->addElement('html', $output->render($renderable));

    }
}
