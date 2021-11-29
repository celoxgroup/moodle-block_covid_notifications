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
 * Notification page where notfications are created and managed.
 *
 * @package    block_covid_notifications
 * @copyright  2021 onwards Celox Group
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Celox Group <info@celoxgroup.com.au>
 */
// Load in Moodle config.
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require_once($CFG->libdir . "/adminlib.php");
require_once($CFG->dirroot . '/blocks/covid_notifications/classes/covidcertificate.php');
require_once($CFG->dirroot . '/cohort/lib.php');
require_once($CFG->libdir . '/formslib.php');


// PARAMS.
$params = array();

$id  = required_param('id', PARAM_INT);

// TODO: Use 'new moodle_url()' instead?
if (isset($id) && $id !== '') {
    $param = '?id=' . $id;
    $xparam = '&id=' . $id;
    $params['id'] = $id;
}

$context = context_system::instance();
$allowedit = has_capability('block/notifications:managenotifications', $context);

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

$record = $guideline->getusercertificatereport($id);

if (empty($record)) {
    throw new moodle_exception('covid_notifications_invalid_record', 'block_covid_notifications');
}

$id = $record->id;
$recorduserid = !empty($record->user_id) ? $record->user_id : "";
$recorduseridarray = explode(" ", $recorduserid);
$userid = $USER->id;
//
// This check for user exist in database.
//
if ($recorduserid) {
    $existuser = $guideline->covid_get_users_by_id($recorduseridarray);
    if (empty($existuser)) {
        throw new moodle_exception('covid_notifications_invalid_user_record', 'block_covid_notifications');
    }
}

$url = new moodle_url('/blocks/covid_notifications/pages/.php');
$PAGE->set_context($context);
$PAGE->set_url($url, $params);

$PAGE->set_title(get_string('page/updaterecord', "block_covid_notifications"));
$PAGE->set_heading(get_string('page/updaterecord', 'block_covid_notifications'));
$PAGE->navbar->add(get_string('blocks'));
$PAGE->navbar->add(get_string('pluginname', 'block_covid_notifications'));
$PAGE->navbar->add(get_string('notifications_table_title_short', 'block_covid_notifications'));

//
// On cancel form submit user redirect.
//
$redirecturl = new moodle_url('/blocks/covid_notifications/pages/view_certificate_report.php');
if (is_siteadmin()) {
    $redirecturl = new moodle_url('/blocks/covid_notifications/pages/admin_report.php');
}

$retype = $record->type;
$remessage = $record->message;
$cid = $record->certificateid;
$curl = !empty($record->vaccinationcertificate) ? new moodle_url($record->vaccinationcertificate) : "";

$mform = new \block_covid_notifications\forms\update_covidcertificate_form(null, array(
    'id' => $id,
    'cid' => $cid,
    'curl' => $curl,
    "userid" => $recorduserid,
    "retype" => $retype,
    "remessage" => $remessage)
);

// Form processing and displaying is done here.
$time = new DateTime("now", core_date::get_server_timezone_object());
$timestamp = $time->getTimestamp();

if ($mform->is_cancelled()) {
    // Handle form cancel operation, if cancel button is present on form.
    redirect($redirecturl);
} else if ($formdata = $mform->get_data()) {
    $data = new stdClass();
    if ($id && confirm_sesskey()) {
        $data->id = $id;
        $data->user_id = $record->user_id;
        $data->vaccinationcertificate = $record->vaccinationcertificate;
        $data->certificateid = $record->certificateid;
        $data->type = $formdata->type;
        $data->message = $formdata->message;
        $data->approved = $formdata->type;
        $data->date_submit = $record->date_submit;
        $data->date_approved = $timestamp;
        $data->approvedby_user_id = $userid;
        $data->timeupdated = $timestamp;
        $guidelineid = $guideline->updateguideline($data);
        if ($guidelineid) {
            $cohortid = $pluginconfig->cohort;
            if ($data->type == 2) {
                cohort_add_member($cohortid, $data->user_id);
            } else {
                cohort_remove_member($cohortid, $data->user_id);
            }
            redirect($redirecturl,
            get_string('updatesuccess', 'block_covid_notifications'),
            null,
            \core\output\notification::NOTIFY_SUCCESS);
        } else {
            redirect($redirecturl,
            get_string('notupdate', 'block_covid_notifications'),
            null,
            \core\output\notification::NOTIFY_ERROR);
        }
    }
} else {
    $mform->set_data($mform);
}
echo $OUTPUT->header();
echo $OUTPUT->container_start();
$mform->display();
echo $OUTPUT->container_end();
echo $OUTPUT->footer();
