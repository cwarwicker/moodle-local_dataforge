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

namespace local_dataforge\fields;

use local_dataforge\traits\choices;

/**
 * Description field class.
 *
 * @package    local_dataforge
 * @copyright  2026 Conn Warwicker <conn@cmrwarwicker.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class select extends \local_dataforge\field {
    use choices;

   #[\Override]
    protected function apply_extra_data(array &$data): void {
        $data['extra'] = ['options' => []];

        // Is this a multi-select or single?
        $multi = $data['options']['multi'] ?? false;

        // Get the value for this field (user submitted or default).
        $value = $this->get_choice_value($multi);

        // Get the choice options.
        $choices = $this->get_choices();

        // Loop through the checkbox options and make them easier to use in mustache.
        foreach ($choices as $choicevalue => $choice) {
           $data['extra']['options'][] = [
               'value' => $choicevalue,
               'name' => $choice,
               'selected' => ($multi) ? (in_array($choicevalue, $value)) : ($value == $choicevalue),
           ];
        }
    }

    /**
     * Get the submitted data for this field.
     * Choices are submitted as an array of values, so we need to implode them into a string.
     *
     * @return mixed
     */
    protected function get_submitted_value(): mixed {
        $options = $this->decode_options();
        if (isset($options['multi']) && $options['multi']) {
            $value = optional_param_array($this->elementid, null, PARAM_RAW);
            return (is_array($value)) ? implode(static::OPTION_DELIM, $value) : null;
        } else {
            return parent::get_submitted_value();
        }
    }

}
