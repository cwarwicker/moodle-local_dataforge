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

use local_dataforge\traits\orm;

/**
 * Form class.
 *
 * @copyright Conn Warwicker <conn@cmrwarwicker.com>
 * @package   local_dataforge
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class form {
    use orm;

    /**
     * @var string The database table the forms are stored in.
     */
    const TABLE = 'local_dataforge';

    /**
     * @var string The type of form.
     */
    public string $type = '' {
        get => $this->type;
    }

    /**
     * @var string The full type name for the form.
     */
    public string $typename {
        get => get_string('form:type:' . $this->type, 'local_dataforge');
    }

    /**
     * @var string The name of the form.
     */
    public string $name = '' {
        get => $this->name;
    }

    /**
     * @var int The context id of the form.
     */
    public int $contextid = 0 {
        get => $this->contextid;
    }

    /**
     * @var string The full context name for the form.
     */
    public string $contextname {
        get {
            $context = \context::instance_by_id($this->contextid);
            return $context->get_context_name();
        }
    }

    /**
     * @var int The user ID of last modified by.
     */
    public int $modifiedby = 0 {
        get => $this->modifiedby;
    }

    /**
     * @var int The time last modified.
     */
    public int $modifiedtime = 0 {
        get => $this->modifiedtime;
    }

    /**
     * @var bool If the form is enabled for use.
     */
    public bool $enabled = false {
        get => $this->enabled;
        set => $this->enabled = (bool)$value;
    }

    /**
     * Build the object
     *
     * @param int $id The database record ID of the field.
     */
    public function __construct(int $id) {
        $this->id = $id;
    }

}
