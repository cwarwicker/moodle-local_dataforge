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

use block_cilp\models\form_field;
use core\exception\moodle_exception;
use local_dataforge\field;

/**
 * Rating form field class.
 *
 * @package    local_dataforge
 * @copyright  2026 Conn Warwicker <conn@cmrwarwicker.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class rating extends field {

    /**
     * @var string The template to use for displaying the value of the field.
     */
    const VALUE_TEMPLATE = 'block_cilp/fields/value/rating';

//    #[\Override]
//    protected function get_value_html(): string {
//
//        global $PAGE;
//
//        $data = [];
//        $data['elementid'] = $this->elementid;
//        $data['options'] = $this->decode_options();
//
//        // Get the actual user value to display (in whatever format suits this field type) or just '-' to denote no data.
//        $userdata = $this->format_user_data($this->get_value());
//        $data['value'] = $userdata ?? 0;
//
//        // Load the generic field mustache template, passing through the specific field's HTML to display.
//        return $PAGE->get_renderer('block_cilp')->render_from_template(static::VALUE_TEMPLATE, $data);
//
//    }

}