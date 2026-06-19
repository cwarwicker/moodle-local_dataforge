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

use local_dataforge\collection;

/**
 * ORM trait for common DB helper methods.
 *
 * @copyright Conn Warwicker <conn@cmrwarwicker.com>
 * @package   local_dataforge
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
trait orm {
    /**
     * @var int The database record ID of the field.
     */
    protected int $id = 0 {
        get => $this->id;
    }

    /**
     * Check if the record which was loaded into this object actually exists and was mapped successfully.
     *
     * @return bool
     */
    public function exists(): bool {
        return ($this->id > 0);
    }

    /**
     * Delete this record from the database.
     *
     * @return bool
     */
    public function delete(): bool {
        global $DB;
        return $DB->delete_records(static::table(), ['id' => $this->id]);
    }

    /**
     * Map data from the DB record to the object.
     *
     * @param \stdClass $data
     * @return void
     */
    protected function map(\stdClass $data): void {
        // Convert data to array.
        $data = (array)$data;

        // Loop through all keys in the array.
        foreach ($data as $key => $value) {
            // If this class has a property for that key, map the data to it.
            if (property_exists($this, $key)) {
                // Assuming we have a method to do so, of course.
                $method = 'map_' . $key;
                if (method_exists($this, $method)) {
                    $this->$method($value);
                } else {
                    $this->$key = $value;
                }
            }
        }
    }

    /**
     * Save a record of this object into its database table.
     *
     * @return int|false Either the record ID if successful, or false
     */
    public function save() {
        global $DB;

        // Get all the properties on the object to find out what keys will need inserting/updating.
        // Any which exist on the object but not in the database table will be ignored by Moodle's database API.
        $obj = new \stdClass();
        foreach (array_keys(get_object_vars($this)) as $key) {
            $obj->$key = $this->$key;
        }

        // If it already exists, we can update it.
        if ($this->exists()) {
            // If the update is successful, return the object id, otherwise false.
            return ($DB->update_record(static::table(), $obj)) ? $this->id : false;
        } else {
            // If it's new, we will need to pass the values through into this method.
            unset($obj->id);
            $this->id = $DB->insert_record(static::table(), $obj);
            return $this->id;
        }
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
     * Get the table to be used for the class. Must be defined as a const on the class using this trait.
     *
     * @return string
     * @throws \LogicException
     */
    public static function table(): string {
        if (!defined('static::TABLE')) {
            throw new \LogicException('TABLE constant must be defined in the class using orm trait.');
        }
        return static::TABLE;
    }

    /**
     * Helper static function to return an instance of an object.
     *
     * @param \stdClass $data Data from the DB record to be mapped to object.
     * @return static Returns a new instance of the class this is called on.
     */
    public static function load(\stdClass $data): static {
        $class = get_called_class();
        $obj = new $class($data->id);
        $obj->map($data);
        return $obj;
    }

    /**
     * Get all of the records in the table, matching the filters, and return the objects.
     *
     * @param array $filters Array of filters.
     * @return collection
     */
    public static function all(array $filters = []): collection {
        global $DB;
        $items = [];
        $records = $DB->get_records(static::table(), $filters);
        if ($records) {
            foreach ($records as $record) {
                $obj = static::load($record);
                if ($obj && $obj->exists()) {
                    $items[$record->id] = $obj;
                }
            }
        }
        return new collection($items);
    }

    /**
     * Find a specific record of this type and return the object.
     *
     * @param array $filters Conditions to be passed into the get_record() method.
     * @return static|null Either the object instance, or null if not found.
     */
    public static function find(array $filters): ?static {
        global $DB;

        $record = $DB->get_record(static::table(), $filters);
        if ($record) {
            $obj = static::load($record);
            if ($obj->exists()) {
                return $obj;
            }
        }

        return null;
    }

    /**
     * Given an array of data, load an object of this instance, passing that data in.
     * To be used when creating a new object, with no ID.
     *
     * @param array $data Array of data to map onto the new object.
     * @return mixed
     */
    public static function from_array(array $data): static {
        // Set the ID to 0 for loading in the constructor.
        $data['id'] = 0;

        // Load and return the object.
        return static::load((object)$data);
    }

}