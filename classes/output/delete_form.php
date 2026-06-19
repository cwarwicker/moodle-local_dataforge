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
use local_dataforge\form;
use moodle_url;
use stdClass;

/**
 * Delete form renderable.
 *
 * @copyright Conn Warwicker <conn@cmrwarwicker.com>
 * @package   local_dataforge
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class delete_form implements \renderable, \templatable {

    /**
     * @var form The form to delete.
     */
    protected form $form;

    /**
     * @var moodle_url Page URL
     */
    protected moodle_url $url;

    /**
     * Construct the renderable.
     *
     * @param form $form Form object.
     */
    public function __construct(form $form, moodle_url $url) {
        $this->form = $form;
        $this->url = $url;
    }

    /**
     * Export the data for the template.
     *
     * @param \core\output\renderer_base $output
     * @return \stdClass
     */
    public function export_for_template(\core\output\renderer_base $output): \stdClass {
        $data = new \stdClass();
        $data->form = $this->form->to_array();
        $data->url = $this->url;
        $data->sesskey = sesskey();
        return $data;
    }
}