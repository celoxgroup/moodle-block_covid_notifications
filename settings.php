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
 * Advanced Notifications block settings
 *
 * @package    block_covid_notifications
 * @copyright  2021 onwards Celox Group
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Celox Group <info@celoxgroup.com.au>
 */

defined('MOODLE_INTERNAL') || die;
global $CFG;
require_once($CFG->dirroot . '/blocks/covid_notifications/lib.php');
if ($ADMIN->fulltree) {
    // SETTINGS HEADING.
    $settings->add(
        new admin_setting_heading(
            'block_covid_notifications/settings', // NAME.
            get_string('setting/settings', 'block_covid_notifications'), // TITLE.
            null
        )
    );

    // ENABLE TOGGLE.
    $settings->add(
        new admin_setting_configcheckbox(
            'block_covid_notifications/enable', // NAME.
            get_string('setting/enable', 'block_covid_notifications'), // TITLE.
            get_string('setting/enable_desc', 'block_covid_notifications'), // DESCRIPTION.
            get_string('setting/enable_default', 'block_covid_notifications') // DEFAULT.
        )
    );

    $settings->add(
        new admin_setting_configtext(
            'block_covid_notifications/notificationtitle', // NAME.
            get_string('setting/notificationtitle', 'block_covid_notifications'), // TITLE.
            get_string('setting/notificationtitle_desc', 'block_covid_notifications'), // DESCRIPTION.
            get_string('setting/notificationtitle_default', 'block_covid_notifications') // DEFAULT.
        )
    );

    $settings->add(
        new admin_setting_configcheckbox(
            'block_covid_notifications/notificationtitleenable', // NAME.
            get_string('setting/notificationtitleenable', 'block_covid_notifications'), // TITLE.
            get_string('setting/notificationtitleenable_desc', 'block_covid_notifications'), // DESCRIPTION.
            get_string('setting/notificationtitleenable_default', 'block_covid_notifications') // DEFAULT.
        )
    );

    $options = block_covid_notifications_getusersrole();
    $settings->add(
        new admin_setting_configmultiselect(
            'block_covid_notifications/approvedrole', // NAME.
            get_string('setting/approvedrole', 'block_covid_notifications'), // TITLE.
            get_string('setting/approvedrole_desc', 'block_covid_notifications'), // DESCRIPTION.
            [array_keys($options)[0]], // DEFAULT.
            $options // OPTIONS.
        )
    );

    $settings->add(
        new admin_setting_confightmleditor(
            'block_covid_notifications/covidmessage', // NAME.
            get_string('setting/covidmessage', 'block_covid_notifications'), // TITLE.
            get_string('setting/covidmessage_desc', 'block_covid_notifications'), // DESCRIPTION.
            get_string(
                'setting/covidmessage_default',
                'block_covid_notifications') // DEFAULT.                                               // OPTIONS.
        )
    );

    $settings->add(
        new admin_setting_confightmleditor(
            'block_covid_notifications/covidpendingmessage', // NAME.
            get_string(
                'setting/covidpendingmessage',
                'block_covid_notifications'), // TITLE.
            get_string('setting/covidpendingmessage_desc', 'block_covid_notifications'), // DESCRIPTION.
            get_string(
                'setting/covidpendingmessage_default',
                'block_covid_notifications') // DEFAULT.                                                        // OPTIONS.
        )
    );

    $settings->add(
        new admin_setting_confightmleditor(
            'block_covid_notifications/covidnotapprovemessage', // NAME.
            get_string('setting/covidnotapprovemessage', 'block_covid_notifications'), // TITLE.
            get_string('setting/covidnotapprovemessage_desc', 'block_covid_notifications'), // DESCRIPTION.
            get_string(
                'setting/covidnotapprovemessage_default',
                'block_covid_notifications') // DEFAULT.                                                           // OPTIONS.
        )
    );

    $options = block_covid_notifications_getallcohort();
    $settings->add(
        new admin_setting_configselect(
            'block_covid_notifications/cohort', // NAME.
            get_string('setting/cohort', 'block_covid_notifications'), // TITLE.
            get_string('setting/cohort_desc', 'block_covid_notifications'), // DESCRIPTION.
            array_keys($options)[0], // DEFAULT.
            $options // OPTIONS.
        )
    );

    $options = block_covid_notifications_getnotificationtype();
    $settings->add(
        new admin_setting_configselect(
            'block_covid_notifications/notificationtype', // NAME.
            get_string('setting/notificationtype', 'block_covid_notifications'), // TITLE.
            get_string('setting/notificationtype_desc', 'block_covid_notifications'), // DESCRIPTION.
            array_keys($options)[0], // DEFAULT.
            $options // OPTIONS.
        )
    );

    // ENABLE TOGGLE.
    $settings->add(
        new admin_setting_configcheckbox(
            'block_covid_notifications/dismissible', // NAME.
            get_string('setting/dismissible', 'block_covid_notifications'), // TITLE.
            get_string('setting/dismissible_desc', 'block_covid_notifications'), // DESCRIPTION.
            get_string('setting/dismissible_default', 'block_covid_notifications') // DEFAULT.
        )
    );

    // ENABLE TOGGLE.
    $settings->add(
        new admin_setting_configcheckbox(
            'block_covid_notifications/icon', // NAME.
            get_string('setting/icon', 'block_covid_notifications'), // TITLE.
            get_string('setting/icon_desc', 'block_covid_notifications'), // DESCRIPTION.
            get_string('setting/icon_default', 'block_covid_notifications') // DEFAULT.
        )
    );

    $settings->add(
        new admin_setting_confightmleditor(
            'block_covid_notifications/emailmessagetext', // NAME.
            get_string('setting/emailmessagetext', 'block_covid_notifications'), // TITLE.
            get_string('setting/emailmessagetext_desc', 'block_covid_notifications'), // DESCRIPTION.
            get_string('setting/emailmessagetext_default', 'block_covid_notifications') // DEFAULT.
        )
    );
}
