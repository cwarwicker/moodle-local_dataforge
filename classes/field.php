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
use core\exception\moodle_exception;
use local_dataforge\traits\orm;
use ReflectionClass;

/**
 * Form field abstract class to be inherited by all field types.
 *
 * @package    local_dataforge
 * @copyright  2026 Conn Warwicker <conn@cmrwarwicker.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class field {
    use orm;

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
        get => $this->uservalue ?? $this->data?->default ?? '';
        set => $this->uservalue = $value;
    }

    /**
     * @var int|null The ID of the user whose data will be loaded.
     */
    protected ?int $userid = null;

    /**
     * @var int|null The database record ID of the user's data for this field.
     */
    protected ?int $userdataid = null;

    /**
     * Build the object
     *
     * @param int $id The database record ID of the field.
     */
    public function __construct(int $id) {
        $this->id = $id;
        $this->elementid = 'local_dataforge_field-' . $this->id;

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
        return json_decode($this->data?->options ?? '', true);
    }

    /**
     * Apply extra data for specific field types.
     *
     * @param array &$data
     * @return void
     */
    protected function apply_extra_data(array &$data): void {}

    /**
     * Apply extra data for specific field types when displaying value.
     *
     * @param array $data
     * @return void
     */
    protected function apply_extra_value_data(array &$data): void {}

    /**
     * Render the field for editing.
     *
     * @return string
     */
    public function render(): string {
        global $PAGE;

        $template = 'local_dataforge/fields/' . $this->type;

        // Convert field to array to pass to template.
        $data = $this->to_array();
        $data['title'] = $data['data']?->title ?? null;
        $data['options'] = $this->decode_options();
        $data['instructions'] = $data['data']?->instructions ?? null;

        // Apply any extra data required for specific types.
        $this->apply_extra_data($data);

        $data['_value'] = $this->uservalue;
        $data['_field'] = $PAGE->get_renderer('local_dataforge')->render_from_template($template, $data);


        // Load the generic field mustache template, passing through the specific field's HTML to display.
        return $PAGE->get_renderer('local_dataforge')
            ->render_from_template('local_dataforge/fields/field', $data);
    }

    /**
     * Display the value of the field.
     *
     * @return string
     */
    public function display(): string {
        global $PAGE;

        $data = $this->to_array();
        $data['title'] = $data['data']?->title ?? null;
        $data['options'] = $this->decode_options();
        $data['instructions'] = false;

        // Apply any extra data required for specific types.
        $this->apply_extra_data($data);
        $this->apply_extra_value_data($data);

        // Load the value of the field for display.
        $data['_field'] = $this->get_value_html($data);

        // Load the generic field mustache template, passing through the specific field's HTML to display.
        return $PAGE->get_renderer('local_dataforge')
            ->render_from_template('local_dataforge/fields/field', $data);
    }

    /**
     * Return the simple HTML for displaying the value of the field, in non-editing mode.
     * This should be overridden by any form fields which do not use the simple template, of just display a text value.
     *
     * @param array $data
     * @return string
     */
    protected function get_value_html(array $data): string {
        global $PAGE;

        $data['elementid'] = $this->elementid;

        // Get the actual user value to display (in whatever format suits this field type) or just '-' to denote no data.
        $userdata = $this->format_user_data($this->uservalue);
        $data['value'] = $userdata ?? '-';

        // Load the generic field mustache template, passing through the specific field's HTML to display.
        return $PAGE->get_renderer('local_dataforge')
            ->render_from_template(static::VALUE_TEMPLATE, $data);
    }

    /**
     * This method should be overwritten by specific field type classes, depending on the format required.
     *
     * @param mixed $value
     * @return mixed
     */
    protected function format_user_data(mixed $value): mixed {
        return $value;
    }

    /**
     * Get the submitted data for this field.
     *
     * @return mixed
     */
    protected function get_submitted_value(): mixed {
        return optional_param($this->elementid, null, PARAM_RAW);
    }

    /**
     * Load a specific user's data for the field.
     *
     * @param int $userid
     */
    public function load_user(int $userid): void {
        global $DB;

        // Reset the user values incase for some reason we are re-using an object.
        $this->userid = $userid;
        $this->userdataid = null;
        $this->uservalue = null;

        // Now retrieve the data for this user.
        $record = $DB->get_record('local_dataforge_records', ['fieldid' => $this->id, 'userid' => $userid]);
        if ($record) {
            $this->userdataid = $record->id;
            $this->uservalue = $record->value;
        }
    }

    /**
     * Has a user been loaded?
     *
     * @return bool
     */
    public function has_user(): bool {
        return ($this->userid > 0);
    }

    /**
     * Run any extra functions which are required, prior to saving user data.
     *
     * @param mixed $value
     */
    protected function pre_save_user_data(mixed &$value): void {}

    /**
     * Save the user's submitted data for the field.
     *
     * @return bool
     */
    public function save(): bool {
        global $DB;

        // If this type can't be edited, just stop.
        if (!static::IS_EDITABLE) {
            return false;
        }

        // Find the submitted data for this field and convert it to a database-friendly format.
        $value = helper::convert_value_for_db($this->get_submitted_value());

        // If the value is empty, set it to null.
        if ($value === '') {
            $value = null;
        }

        // Run any pre-saving functions which are required by the field type.
        $this->pre_save_user_data($value);

        // If the user already has some data saved for this field, update it.
        if ($this->userdataid) {
            $update = [
                'id' => $this->userdataid,
                'value' => $value,
                'modifiedtime' => time(),
            ];
            $DB->update_record('local_dataforge_records', $update);
        } else {
            $insert = [
                'fieldid' => $this->id,
                'userid' => $this->userid,
                'value' => $value,
                'modifiedtime' => time(),
            ];
            $this->userdataid = $DB->insert_record('local_dataforge_records', $insert);
        }

        $this->uservalue = $value;

        return ($this->userdataid > 0);
    }

    /**
     * Load the field object from its database record ID.
     *
     * @param int $id The database record ID of the field.
     * @return static
     */
    public static function load(int $id): static {
        global $DB;

        // Get the record from the database so we can find out what type it is.
        $record = $DB->get_record(static::TABLE, ['id' => $id], '*', MUST_EXIST);

        // Work out which class to use.
        $class = 'local_dataforge\fields\\' . strtolower($record->type);
        if (!class_exists($class)) {
            throw new coding_exception(get_string('error:type:invalid', 'local_dataforge', $record->type));
        }

        // Create the object.
        $obj = new $class($record->id);
        $obj->data = $record->data;
        return $obj;
    }

}
