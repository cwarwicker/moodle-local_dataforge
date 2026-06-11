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

namespace local_dataforge;

/**
 * Helper class with re-usable methods.
 *
 * @package    local_dataforge
 * @copyright  2026 Conn Warwicker <conn@cmrwarwicker.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class helper {

    /**
     * Convert a value so that it can be inserted into the database (scalar or json).
     *
     * @param mixed $value The value to convert
     * @return mixed Either the value itself, if it is already scalar, or a json-encoded string.
     */
    public static function convert_value_for_db(mixed $value): mixed {
        // If it is a scalar value, the only check we want to do is see if it's a string, in which case trim it.
        if (is_scalar($value)) {
            return (is_string($value)) ? trim($value) : $value;
        } else {
            // Otherwise, if it is null, simply return it. If it's not null, json_encode it, as it's probably an array.
            return (!is_null($value)) ? json_encode($value) : $value;
        }
    }

}
