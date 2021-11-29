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
 * Executes on plugin upgrade.
 *
 * @package    block_covid_notifications
 * @copyright  2021 onwards Celox Group
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Celox Group <info@celoxgroup.com.au>
 */

defined('MOODLE_INTERNAL') || die;

/**
 * When upgrading plugin, execute the following code.
 *
 * @param int $oldversion Previous version of plugin (from DB).
 * @return bool Successful upgrade or not.
 */
function xmldb_block_covid_notifications_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();
    // This was needed to add tables during development - probably not even need anymore. Leaving just-in-case (for now).
    if ($oldversion < 2017100217) {
        // Define table block_covid_notifications to be created.
        $table = new xmldb_table('block_covid_notifications');

        // Adding fields to table block_covid_notifications.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('user_id', XMLDB_TYPE_INTEGER, '11', null, XMLDB_NOTNULL, null, null);
        $table->add_field('vaccinationcertificate', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('certificateid', XMLDB_TYPE_INTEGER, '11', null, XMLDB_NOTNULL, null, '0000000000');
        $table->add_field('type', XMLDB_TYPE_CHAR, '30', null, XMLDB_NOTNULL, null, 'info');
        $table->add_field('message', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('approved', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, null);
        $table->add_field('date_submit', XMLDB_TYPE_INTEGER, '11', null, XMLDB_NOTNULL, null, '0000000000');
        $table->add_field('date_approved', XMLDB_TYPE_INTEGER, '11', null, XMLDB_NOTNULL, null, '0000000000');
        $table->add_field('approvedby_user_id', XMLDB_TYPE_INTEGER, '11', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timeupdated', XMLDB_TYPE_INTEGER, '11', null, XMLDB_NOTNULL, null, '0000000000');

        // Adding keys to table block_covid_notifications.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for block_covid_notifications.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        // Notifications savepoint reached.
        upgrade_block_savepoint(true, 2017100217, 'notifications');
    }
    // Add future upgrade points here.
    return true;
}
