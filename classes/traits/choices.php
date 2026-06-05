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

namespace local_dataforge\traits;

/**
 * Trait to allow form fields to have options.
 *
 * @package    local_dataforge
 * @copyright  2026 Conn Warwicker <conn@cmrwarwicker.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
trait choices {
    /**
     * Get the choices array.
     *
     * @return array
     */
    public function get_choices(): array {
        return $this->decode_options()['choices'];
    }

    /**
     * Get the current value of the field.
     *
     * @param bool $multiple If there can be multiple values, such as from a checkbox or multi-select field.
     * @return mixed
     */
    protected function get_choice_value(bool $multiple = false): mixed {
        return ($multiple) ? explode(static::OPTION_DELIM, $this->uservalue) : $this->uservalue;
    }

    /**
     * Given the 'value' saved for a choice, get the corresponding display value.
     *
     * @param string $value
     * @return string
     */
    protected function get_choice_display_from_value(string $value): string {

        // Get the array of choices to go through.
        $choices = $this->get_choices();

        // If this value exists as a key, return its display value. Otherwise, return empty string.
        return (array_key_exists($value, $choices)) ? $choices[$value] : '';

    }

    /**
     * Convert the array of checked options to a list string of the choice names.
     * @param mixed $value
     * @return string
     */
    public function format_user_data($value): string {
        if (is_null($value) || $value === false) {
            return '-';
        }

        // Convert each select option from its key to its actual value for display.
        $values = array_map([$this, 'get_choice_display_from_value'], explode(static::OPTION_DELIM, $value));

        // Then return in a string list.
        return implode(static::OPTION_DELIM, $values);
    }

}