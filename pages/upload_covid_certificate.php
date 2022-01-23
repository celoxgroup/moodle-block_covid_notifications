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
require_once($CFG->libdir . '/formslib.php');

require_login();
require_sesskey();

$context = context_system::instance();
if (!has_capability('moodle/user:editownprofile', $context)) {
    throw new moodle_exception('covid_notifications_err_nocapability', 'block_covid_notifications');
}

$url = new moodle_url('/blocks/covid_notifications/pages/upload_covid_certificate.php');
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title(get_string('page:certicateupload', "block_covid_notifications"));
$PAGE->set_heading(get_string('page:certicateupload', 'block_covid_notifications'));
$PAGE->navbar->add(get_string('blocks'));
$PAGE->navbar->add(get_string('pluginname', 'block_covid_notifications'));
$PAGE->navbar->add(get_string('notifications_table_title_short', 'block_covid_notifications'));

$redirecturl = new moodle_url('/my');
$userid = $USER->id;

if ($record = $DB->get_record('block_covid_notifications', array('user_id' => $userid), '*', IGNORE_MISSING)) {
    $cid = $record->certificateid;
    if ($record->approved == 0 || $record->approved == 2) {
        throw new moodle_exception('covid_notifications_err_nocapability', 'block_covid_notifications');
    }
}
$id = !empty($record->id) ? $record->id : "";
$mform = new \block_covid_notifications\forms\upload_covidcertificate_form();
$guideline = new block_covid_notifications_handel();

// From user name who send email used in message.
$emailsendbyname = !empty($USER->username) ? get_string('email/sentby', 'block_covid_notifications') . $USER->username : "";
$time = new DateTime("now", core_date::get_server_timezone_object());
$timestamp = $time->getTimestamp();

// Form processing and displaying is done here.
if ($mform->is_cancelled()) {
    // Handle form cancel operation, if cancel button is present on form.
    redirect($redirecturl);
} else if ($mform->is_submitted() && $mform->is_validated() && confirm_sesskey() && $formdata = $mform->get_data()) {
    // Get covidcertificate url.
    $covidcertificate = $guideline->getimagefullurl($formdata->vaccinationcertificate, $context->id);
    // Get Selected user who get the email of certificate.
    $pluginconfig = get_config('block_covid_notifications');
    $touser = $guideline->getuserbyselectedid($pluginconfig->approvedrole);
    $emailmessage = $pluginconfig->emailmessagetext;
    $data = new stdClass();
    if ($id) {
        $data->id = $id;
        $data->user_id = $USER->id;
        $data->vaccinationcertificate = $covidcertificate;
        $data->certificateid = $formdata->vaccinationcertificate;
        $data->type = "";
        $data->message = "";
        $data->approved = "0";
        $data->date_submit = $timestamp;
        $data->date_approved = "";
        $data->approvedby_user_id = "";
        $data->timeupdated = $timestamp;
        $guidelineid = $guideline->updateguideline($data);

        if ($guidelineid) {
            $emailupdatemessage = format_text($emailmessage) .
                                  new moodle_url('/blocks/covid_notifications/pages/view_certificate_report.php') .
                                  "<br/>" . new moodle_url($covidcertificate);

            $emailupdatetitle = get_string('email/emailtitle', 'block_covid_notifications') ." ". $emailsendbyname;
            // In case touser is empty.
            if (empty($touser)) {
                redirect($redirecturl,
                get_string('emailsend', 'block_covid_notifications'),
                null,
                \core\output\notification::NOTIFY_SUCCESS);
            }
            // In case touser is exist.
            foreach ($touser as $receveduser) {
                email_to_user($receveduser, $USER, $emailupdatetitle, $emailupdatemessage);
            }
            redirect($redirecturl, get_string('emailsend', 'block_covid_notifications'),
            null, \core\output\notification::NOTIFY_SUCCESS);

        } else {
            redirect($url,
            get_string('notsave', 'block_covid_notifications'),
            null,
            \core\output\notification::NOTIFY_ERROR);
        }
    } else {
        $data->user_id = $USER->id;
        $data->vaccinationcertificate = $covidcertificate;
        $data->certificateid = $formdata->vaccinationcertificate;
        $data->type = '';
        $data->message = "";
        $data->approved = '';
        $data->date_submit = $timestamp;
        $data->date_approved = '';
        $data->approvedby_user_id = '';
        $data->timeupdated = $timestamp;

        $guidelineid = $guideline->insertguideline($data);

        if ($guidelineid) {

            $emailupdatemessage = format_text($emailmessage) .
                                    new moodle_url('/blocks/covid_notifications/pages/view_certificate_report.php') .
                                    "<br/>" . new moodle_url($covidcertificate);
            $emailupdatetitle = get_string('email/emailtitle', 'block_covid_notifications') . " " .  $emailsendbyname;

            // In case touser is empty.
            if (empty($touser)) {
                redirect($redirecturl, get_string('emailsend', 'block_covid_notifications'),
                null,
                \core\output\notification::NOTIFY_SUCCESS);
            }
            // In case touser is exist.
            foreach ($touser as $receveduser) {
                email_to_user($receveduser, $USER, $emailupdatetitle, $emailupdatemessage);
            }
            redirect($redirecturl, get_string('emailsend', 'block_covid_notifications'),
            null, \core\output\notification::NOTIFY_SUCCESS);
        } else {
            redirect($redirecturl, get_string('notsave', 'block_covid_notifications'),
            null, \core\output\notification::NOTIFY_ERROR );
        }
    }
}
echo $OUTPUT->header();
echo $OUTPUT->container_start();
$mform->display();
echo $OUTPUT->container_end();
echo $OUTPUT->footer();
