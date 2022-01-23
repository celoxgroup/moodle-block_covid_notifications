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

/**
 * Block version details
 *
 * @package    block_covid_notifications
 * @copyright  2021 onwards Celox Group
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Celox Group <info@celoxgroup.com.au>
 **/

namespace block_covid_notifications\privacy;

use context_block;
use context_system;
use core_privacy\local\metadata\collection;
use \core_privacy\local\metadata\provider as metadata_provider;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\approved_userlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\core_userlist_provider;
use core_privacy\local\request\plugin\provider as plugin_provider;
use core_privacy\local\request\transform;
use core_privacy\local\request\userlist;
use core_privacy\local\request\writer;

defined('MOODLE_INTERNAL') || die();

/** @var string Flag used to determine if notification is block-based or global */
const SITE_NOTIFICATION = "-1";

/**
 * Class provider - extends core to leverage the Privacy API.
 *
 * @copyright  2016 onwards LearningWorks Ltd {@link https://learningworks.co.nz/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
        // This plugin stores personal user data.
        metadata_provider,

        // Data is provided directly to core.
        plugin_provider,

        // This plugin is can determine which users' data it's captured.
        core_userlist_provider {

    /**
     * Get metadata about a user used by the plugin.
     *
     * @param   collection $collection The collection of metadata.
     * @return  collection  $collection The collection returned as a whole.
     */
    public static function get_metadata(collection $collection) : collection {
        // Add items to collection.
        $collection->add_database_table(
            'block_covid_notifications',
            [
                'user_id' => 'privacy:metadata:block_covid_notifications:user_id',
                'certificateid' => 'privacy:metadata:block_covid_notifications:certificateid',
                'message' => 'privacy:metadata:block_covid_notifications:message',
                'type' => 'privacy:metadata:block_covid_notifications:type',
                'approved' => 'privacy:metadata:block_covid_notifications:approved',
                'date_submit' => 'privacy:metadata:block_covid_notifications:date_submit',
                'date_approved' => 'privacy:metadata:block_covid_notifications:date_approved',
                'approvedby_user_id' => 'privacy:metadata:block_covid_notifications:approvedby_user_id',
                'timeupdated' => 'privacy:metadata:block_covid_notifications:timeupdated'
            ],
            'privacy:metadata:block_covid_notifications'
        );

        $collection->add_subsystem_link(
            'block_covid_notifications',
            [
                'vaccinationcertificate' => 'privacy:metadata:block_covid_notifications:vaccinationcertificate',
            ],
            'privacy:metadata:block_covid_notifications'
        );
        return $collection;
    }
}

