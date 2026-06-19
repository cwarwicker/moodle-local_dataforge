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
 * Collection class.
 *
 * @copyright Conn Warwicker <conn@cmrwarwicker.com>
 * @package   local_dataforge
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class collection {

    /**
     * @var array The items in the collection.
     */
    private array $items = [];

    /**
     * Construct the collection.
     *
     * @param array $items
     */
    public function __construct(array $items) {
        $this->items = $items;
    }

    /**
     * Get the underlying array from the collection.
     *
     * @return array
     */
    public function all(): array {
        return $this->items;
    }

    /**
     * Convert the collection objects to arrays.
     * The objects must use the orm trait to have the to_array method.
     *
     * @return array
     */
    public function to_array(): array {
        // Start a new array to remove old keys.
        $return = [];
        $items = $this->items;
        foreach ($items as $item) {
            $return[] = $item->to_array();
        }
        return $return;
    }


}
