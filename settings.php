<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Admin settings for local_dataforge.
 *
 * @copyright Conn Warwicker <conn@cmrwarwicker.com>
 * @package   local_dataforge
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {

    $ADMIN->add(
        'localplugins',
        new admin_category('dataforgecategory', get_string('pluginname', 'local_dataforge'))
    );

    $ADMIN->add(
        'dataforgecategory',
        new admin_externalpage(
            'local_dataforge_manage',
            get_string('manageforms', 'local_dataforge'),
            new moodle_url('/local/dataforge/manage.php'),
            'moodle/site:config'
        )
    );

//    $settings = new admin_settingpage(
//        'local_dataforge',
//        get_string('pluginname', 'local_dataforge')
//    );
//    $ADMIN->add('localplugins', $settings);
}
