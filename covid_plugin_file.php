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
 * This script delegates file serving to individual plugins
 *
 * @package    block_covid_notifications
 * @copyright  2021 onwards Celox Group
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Celox Group <info@celoxgroup.com.au>
 */
// Disable moodle specific debug messages and any errors in output.
require_once('../../config.php');
require_once($CFG->libdir . "/filelib.php");
require_once($CFG->dirroot . '/blocks/covid_notifications/classes/covidcertificate.php');

$context = context_system::instance();
$allowedit = has_capability('block/covid_notifications:managenotifications', $context);

require_login();

$guideline = new block_covid_notifications_handel();
$pluginconfig = get_config('block_covid_notifications');

$managerallow = false;
if (!$allowedit) {
    $touser = $guideline->getuseremailbyroleid($pluginconfig->approvedrole);
    if (in_array($USER->email, $touser)) {
        $managerallow = true;
    }
}

if (!$allowedit && !$managerallow) {
    throw new moodle_exception('covid_notifications_err_nocapability', 'block_covid_notifications');
}

if (empty($relativepath)) {
    $relativepath = get_file_argument();
}
$forcedownload = optional_param('forcedownload', 0, PARAM_BOOL);
$preview = optional_param('preview', null, PARAM_ALPHANUM);
// Offline means download the file from the repository and serve it, even if it was an external link.
// The repository may have to export the file to an offline format.
$offline = optional_param('offline', 0, PARAM_BOOL);
$embed = optional_param('embed', 0, PARAM_BOOL);
file_pluginfile($relativepath, $forcedownload, $preview, $offline, $embed);
