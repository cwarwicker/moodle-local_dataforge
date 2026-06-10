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

use core\exception\coding_exception;
use ReflectionClass;

/**
 * Form field abstract class to be inherited by all field types.
 *
 * @package    local_dataforge
 * @copyright  2026 Conn Warwicker <conn@cmrwarwicker.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class field {
    /**
     * @var string The database table the fields are stored in.
     */
    const TABLE = 'local_dataforge_fields';

    /**
     * @var string The template to use for displaying the value of the field.
     */
    const VALUE_TEMPLATE = 'local_dataforge/fields/value/simple';

    /**
     * @var string Delimiter used to separate data saved in 1 column.
     */
    const OPTION_DELIM = ',';

    /**
     * @var bool Can the field have instructions?
     */
    const CAN_HAVE_INSTRUCTIONS = true;

    /**
     * @var bool Is the field editable?
     */
    const IS_EDITABLE = true;

    /**
     * @var int The database record ID of the field.
     */
    protected int $id = 0 {
        get => $this->id;
    }

    /**
     * @var string The type of field.
     */
    public string $type = '' {
        get => $this->type;
    }

    /**
     * @var mixed The data for the field.
     */
    public mixed $data = null {
        get => json_decode($this->data);
        set => $this->data = (is_string($value)) ? $value : json_encode($value);
    }

    /**
     * @var string The unique ID for the form field on the page.
     */
    public string $elementid = '';

    /**
     * @var mixed|null The saved value from the user, or the default value if not set.
     */
    public mixed $uservalue = null {
        get => $this->uservalue ?? $this->data?->default;
        set => $this->uservalue = $value;
    }

    /**
     * Build the object
     *
     * @param int|null $id The database record ID of the field.
     */
    public function __construct(?int $id = null) {
        if (!is_null($id)) {
            $this->id = $id;
            $this->elementid = 'field-' . $this->id;
        }

        // Set the type property from the class name.
        $reflect = new ReflectionClass($this);
        $this->type = $reflect->getShortName();
    }

    /**
     * Decode the options for use in template.
     * This is json encoded within the already json decoded $data property.
     *
     * @return array|null
     */
    protected function decode_options(): ?array {
        return json_decode($this->data?->options, true);
    }

    /**
     * Apply extra data for specific field types.
     *
     * @param array &$data
     * @return void
     */
    protected function apply_extra_data(array &$data): void {}

    public function render(): string {
        global $PAGE;

        $template = 'local_dataforge/fields/' . $this->type;

        // Convert field to array to pass to template.
        $data = $this->to_array();
        $data['title'] = $data['data']?->title;
        $data['options'] = $this->decode_options();
        $data['instructions'] = $data['data']?->instructions;

        // Apply any extra data required for specific types.
        $this->apply_extra_data($data);

        $data['_value'] = $this->uservalue;
        $data['_field'] = $PAGE->get_renderer('local_dataforge')->render_from_template($template, $data);

        // Load the generic field mustache template, passing through the specific field's HTML to display.
        return $PAGE->get_renderer('local_dataforge')
            ->render_from_template('local_dataforge/fields/field', $data);

    }

    /**
     * Convert the field to an array for template.
     *
     * @return array
     */
    public function to_array() : array {
        $array = [];
        foreach (get_object_vars($this) as $key => $value) {
            $array[$key] = $value;
        }
        return $array;
    }

    /**
     * Create a field from an array of data.
     *
     * @param array $data
     * @return static
     * @throws coding_exception
     */
    public static function from_array(array $data): static {
        $class = get_called_class();

        // If we've called this from a specific field class, load the data we passed through.
        if ($class !== 'local_dataforge\field') {
            return static::load($data);
        }

        // If we haven't got the type, we don't know what to do.
        if (!isset($data['type'])) {
            throw new coding_exception(get_string('error:type:invalid', 'local_dataforge', '?'));
        }

        // Make sure that the requested form field type has a valid class.
        $class = 'local_dataforge\fields\\' . strtolower($data['type']);
        if (!class_exists($class)) {
            throw new coding_exception(get_string('error:type:invalid', 'local_dataforge', $data['type']));
        }

        // Remove the type from the data, and then call it again using the correct class.
        unset($data['type']);
        return $class::from_array($data);

    }

    /**
     * Load a field from an array of data.
     *
     * @param array $data
     * @return static
     */
    protected static function load(array $data): static {
        $obj = new static();
        $obj->data = $data;
        $obj->elementid = 'field-fake-' . mt_rand(1, 100000);
        return $obj;
    }
}
