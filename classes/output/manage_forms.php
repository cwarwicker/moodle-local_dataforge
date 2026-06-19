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

namespace local_dataforge\output;

use local_dataforge\collection;
use stdClass;

/**
 * Manage forms page renderable.
 *
 * @copyright Conn Warwicker <conn@cmrwarwicker.com>
 * @package   local_dataforge
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class manage_forms implements \renderable, \templatable {

    /**
     * @var collection Collection of form objects.
     */
    protected collection $forms;

    /**
     * Construct the manage forms page.
     *
     * @param collection $forms Collection of form objects.
     */
    public function __construct(collection $forms) {
        $this->forms = $forms;
    }

    /**
     * Export the data for the template.
     *
     * @param \core\output\renderer_base $output
     * @return \stdClass
     */
    public function export_for_template(\core\output\renderer_base $output): \stdClass {
        $data = new \stdClass();
        $data->forms = [];
        foreach ($this->forms->all() as $form) {
            $arr = $form->to_array();
            $data->forms[] = $arr;
        }
        return $data;
    }
}