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

/**
 * Description field class.
 *
 * @package    local_dataforge
 * @copyright  2026 Conn Warwicker <conn@cmrwarwicker.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class matrix extends \local_dataforge\field {
    #[\Override]
    public function apply_extra_data(array &$data): void {
        $value = json_decode($this->uservalue, true);

        // Overwrite value being sent to template.
        $data['matrix'] = ['rows' => []];

        // Loop through the configured rows and build up an array of column values.
        foreach ($data['options']['rows'] as $key => $row) {

            $data['matrix']['rows'][$key] = ['row_id' => $row['row_id'], 'row_name' => $row['row_name'], 'cols' => []];

            // Loop through the columns and set the boolean value, based on the value in $userdata.
            foreach ($data['options']['columns'] as $column) {
                $data['matrix']['rows'][$key]['cols'][] = [
                    'col_id' => $column['col_id'],
                    'col_name' => $column['col_name'],
                    'value' => (($value[$row['row_id']] ?? null) == $column['col_id'])
                ];
            }

        }
    }
}
