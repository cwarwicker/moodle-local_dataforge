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

use local_dataforge\field;

require_once($CFG->dirroot . '/lib/form/filemanager.php');

/**
 * File upload form field class.
 *
 * @package    local_dataforge
 * @copyright  2026 Conn Warwicker <conn@cmrwarwicker.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class file extends field {

    /**
     * @var array Array of default options to use in filemanager fields.
     */
    const FILEMANAGER_OPTIONS = ['subdirs' => 0];

    /**
     * @var string Value to use for the 'filearea' of uploaded files. This will have an ID appended.
     */
    const FILEMANAGER_AREA = 'field_';

    /**
     * @var string Value to use for the 'component' of uploaded files.
     */
    const FILEMANAGER_COMPONENT = 'local_dataforge';

    /**
     * @var string The template to use for displaying the value of the field.
     */
    const VALUE_TEMPLATE = 'local_dataforge/fields/value/file';

    /**
     * Construct the file field object.
     *
     * @param int|null $id
     */
    public function __construct(?int $id = null) {
        parent::__construct($id);

        // Element name must end in _filemanager for Moodle functions to work correctly.
        $this->elementid = $this->elementid . '_filemanager';
    }

    /**
     * Apply field options to the filemanager, overwriting any defaults.
     *
     * @return array
     */
    protected function get_filemanager_options(): array {
        $defaults = static::FILEMANAGER_OPTIONS;
        $options = $this->decode_options();

        if (isset($options['accepted_types'])) {
            $defaults['accepted_types'] = $options['accepted_types'];
        }

        if (isset($options['maxfiles'])) {
            $defaults['maxfiles'] = $options['maxfiles'];
        }

        return $defaults;
    }

    /**
     * Strip the _filemanager suffix from the element id.
     *
     * @return string
     */
    protected function get_stripped_element_name(): string {
        return str_replace('_filemanager', '', $this->elementid);
    }

    /**
     * Get the filearea to use for uploaded files to this form field.
     *
     * @return string
     */
    protected function get_filearea(): string {
        return static::FILEMANAGER_AREA . $this->id;
    }

    #[\Override]
    protected function apply_extra_data(array &$data): void {
        // Build a filemanager form element and pass it through in the mustache template.
        $input = new \MoodleQuickForm_filemanager($this->elementid, $this->data->title, [
            'id' => $this->elementid
        ], $this->get_filemanager_options());

        // Load previously saved files into the filemanager field.
//        if ($this->has_user()) {
//
//            // Form field files are stored against the user's context.
//            $context = \core\context\user::instance($this->userid);
//
//            // Build an object to pass in and be returned, modified.
//            $fielddata = new \stdClass();
//            $fielddata = file_prepare_standard_filemanager($fielddata, $this->get_stripped_element_name(),
//                $this->get_filemanager_options(), $context, static::FILEMANAGER_COMPONENT,
//                $this->get_filearea(), $this->get_item_id());
//
//            // Apply prepared filemanagers to form.
//            $input->setValue($fielddata->{$this->elementname});
//
//        }

        $data['field'] = $input->toHtml();
    }

//    #[\Override]
//    protected function apply_extra_value_data(array &$data): void {
//
//        $files = [];
//        $data['files'] = [];
//
//        // If we've got user data loaded, go and find any files they have attached to this field.
//        if ($this->has_user()) {
//
//            // Form field files are stored against the user's context.
//            $context = \core\context\user::instance($this->userid);
//
//            // Get the file storage and load any files it can find.
//            $fs = get_file_storage();
//            $files = $fs->get_area_files($context->id, static::FILEMANAGER_COMPONENT, $this->get_filearea(),
//                $this->get_item_id(), 'itemid, filepath, filename', false);
//
//        }
//
//        // If we have found any files, loop through them and generate download links.
//        if ($files) {
//            foreach ($files as $file) {
//                $data['files'][] = \html_writer::link(\moodle_url::make_pluginfile_url(
//                    $file->get_contextid(), $file->get_component(), $file->get_filearea(), $file->get_itemid(),
//                    $file->get_filepath(), $file->get_filename(), false),
//                    $file->get_filename());
//            }
//        }
//
//    }
}
