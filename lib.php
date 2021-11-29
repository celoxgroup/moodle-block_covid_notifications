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
 * Library of functions for the plugin to leverage.
 *
 * @package    block_covid_notifications
 * @copyright  2021 onwards Celox Group
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Celox Group <info@celoxgroup.com.au>
 */

defined('MOODLE_INTERNAL') || die();
function block_covid_notifications_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    $fs = get_file_storage();
    if ($filearea == 'attachment') {
        $hash = $fs->get_pathname_hash($context->id, 'block_covid_notifications', 'attachment', $args[0], "/", $args[1]);
        $files = $fs->get_file_by_hash($hash);
        if (!isset($files) || empty($files)) {
            $error = "File not available.";
            $urltogo = new moodle_url('/blocks/covid_notifications/pages/upload_covid_certificate.php');
            redirect($urltogo, $error, null, \core\output\notification::NOTIFY_ERROR);
        }
        send_stored_file($files, 0, 0, false);
    }
}

function block_covid_notifications_getusersrole() {
    global $DB;
    $sql = "SELECT id , archetype
    FROM {role}
    WHERE archetype NOT IN ('guest', 'student', 'user' , 'frontpage')";
    $roles = $DB->get_records_sql($sql);
    $response = [];
    foreach ($roles as $role) {
        $response[$role->id] = ucfirst($role->archetype);
    }
    return $response;
}

function block_covid_notifications_getallcohort() {
    global $DB;
    $selectcohort = get_string('setting/cohort_select', 'block_covid_notifications');
    $cohorts = $DB->get_records('cohort');
    $response = [];
    $response[''] = ucfirst($selectcohort);
    foreach ($cohorts as $cohort) {
        $response[$cohort->id] = ucfirst($cohort->name);
    }
    return $response;
}

function block_covid_notifications_getnotificationtype() {
    $formats = [];
    $formats[''] = get_string('notifications_type', 'block_covid_notifications');
    $formats[strtolower(get_string('notifications_add_option_info_short',
    'block_covid_notifications'))] = get_string('notifications_add_option_info',
    'block_covid_notifications');
    $formats[strtolower(get_string('notifications_add_option_success',
    'block_covid_notifications'))] = get_string('notifications_add_option_success',
    'block_covid_notifications');
    $formats[strtolower(get_string('notifications_add_option_warning',
    'block_covid_notifications'))] = get_string('notifications_add_option_warning',
    'block_covid_notifications');
    $formats[strtolower(get_string('notifications_add_option_danger',
    'block_covid_notifications'))] = get_string('notifications_add_option_danger',
    'block_covid_notifications');
    $formats[strtolower(get_string('notifications_add_option_announcement',
    'block_covid_notifications'))] = get_string('notifications_add_option_announcement',
    'block_covid_notifications');
    return $formats;
}

function block_covid_notifications_get_fontawesome_icon_map() {
    return [
        'block_covid_notifications:i/pinned' => 'fa-map-pin',
        'block_covid_notifications:t/selected' => 'fa-check',
        'block_covid_notifications:t/subscribed' => 'fa-envelope-o',
        'block_covid_notifications:t/unsubscribed' => 'fa-envelope-open-o',
    ];
}
