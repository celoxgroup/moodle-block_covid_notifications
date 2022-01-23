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
 * All the configurable strings used throughout the plugin.
 *
 * @package    block_covid_notifications
 * @copyright  2021 onwards Celox Group
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Celox Group <info@celoxgroup.com.au>
 */

$string['pluginname'] = 'Covid-19 notifications';

// Capabilities.
$string['covid_notifications:addinstance'] = 'Add a new Covid-19 notifications block';
$string['covid_notifications:myaddinstance'] = 'Add a new Covid-19 notifications block to the my Moodle page';
$string['covid_notifications:managenotifications'] = 'Manage notifications and the relative settings';
$string['covid_notifications:manageownnotifications'] = 'Manage own notifications and the relative settings';

// Block Configuration.
$string['notifications'] = 'Covid-19 notifications';
// Manage Advanced Notification Lang Strings.
$string['notifications_table_title'] = 'Manage notifications';
$string['notifications_table_title_short'] = 'Manage';
$string['notifications_table_heading'] = 'Covid-19 notifications';

// New Notification Lang Strings.
$string['notifications_type'] = 'Type';

// Renderer.
$string['notifications_add_heading'] = 'New notification';
$string['notifications_add_option_info'] = 'Information';
$string['notifications_add_option_info_short'] = 'Info';
$string['notifications_add_option_success'] = 'Success';
$string['notifications_add_option_warning'] = 'Warning';
$string['notifications_add_option_danger'] = 'Danger';
$string['notifications_add_option_announcement'] = 'Announcement';

// Admin Settings.

$string['setting/settings'] = 'Settings:';
$string['setting/enable'] = 'Enable Notification:';
$string['setting/enable_desc'] = 'Toggles whether all notifications are enabled/disabled<hr>';
$string['setting/enable_default'] = '';

$string['setting/dateformat'] = 'Date format:';
$string['setting/dateformat_desc'] = 'Dates will be shown in the chosen format.<hr>';

// New Start.
$string['setting/notificationtitle'] = 'Notification Title:';
$string['setting/notificationtitle_desc'] = 'Covid Notification Title';
$string['setting/notificationtitle_default'] = '';

$string['setting/notificationtitleenable'] = 'Display Title?:';
$string['setting/notificationtitleenable_desc'] = 'Display a title above notification';
$string['setting/notificationtitleenable_default'] = '';

$string['setting/approvedrole'] = 'Select Admin Role/s:';
$string['setting/approvedrole_desc'] = 'Selected roles will received email notification on new submissions.';
$string['setting/approvedrole_default'] = 'Manager';

$string['setting/covidmessage'] = 'Notification Message:';
$string['setting/covidmessage_desc'] = 'This message appears on user dashboard.
                                        Shortcodes such as [username], [firstname] and [lastname]
                                        is supported';
$string['setting/covidmessage_default'] = '';

$string['setting/covidpendingmessage'] = 'Pending Notification:';
$string['setting/covidpendingmessage_desc'] = 'This notification appears on user dashboard once the students submit their file/s';
$string['setting/covidpendingmessage_default'] = '';

$string['setting/covidnotapprovemessage'] = 'Not Approved Notification:';
$string['setting/covidnotapprovemessage_desc'] = 'This notification appears on user dashboard,
                                                   if their files has been decliend the message will shown.';
$string['setting/covidnotapprovemessage_default'] = '';

$string['setting/cohort'] = 'Cohort Assignment:';
$string['setting/cohort_desc'] = 'Select a cohort that studnet will become a member once the application has been approved.';
$string['setting/cohort_default'] = '';
$string['setting/cohort_select'] = 'Select cohort';

$string['setting/notificationtype'] = 'Notification Type:';
$string['setting/notificationtype_desc'] = 'Change notification type for a main notification message.';
$string['setting/notificationtype_default'] = '';

$string['setting/dismissible'] = 'Dismissible:';
$string['setting/dismissible_desc'] = '';
$string['setting/dismissible_default'] = '';

$string['setting/icon'] = 'Show Icon?:';
$string['setting/icon_desc'] = '';
$string['setting/icon_default'] = '';
$string['setting/aicon'] = 'aicon';

$string['setting/emailmessagetext'] = 'Email Template:';
$string['setting/emailmessagetext_desc'] = 'This email template will send information about the
                                            submission to selected role/s. You can specify
                                            additional short-codes such as [username], [userfirstname], [userlastname], [useremail] and [userfullname].';
$string['setting/emailmessagetext_default'] = '';

$string['clikupploadbutton'] = 'Upload Vaccination Certificate';
$string['clikaminreport'] = 'View vaccination report';
$string['form/certicateupload'] = 'Upload Vaccination Certificate';
$string['page:certicateupload'] = 'Upload Vaccination Certificate';
$string['uploadcertificatebtn'] = 'Upload Certificate';

$string['form/download'] = 'Download certificate';
$string['form/cliktodownload'] = 'Click here';
$string['form/vaccintype'] = 'Fully Vaccinated?';
$string['form/vaccinmessage'] = 'Outcome/Reason for refusal';
$string['page/updaterecord'] = 'Edit User Information';
$string['page/userreport'] = 'List of all users';
$string['page/adminreport'] = 'Covid-19 certificate report';

$string['save'] = 'Save';
$string['savesuccess'] = 'Thank you for submitting your certificate, we will get back to you withing few days.';
$string['notsave'] = 'Something went wrong. Please try again or contact Student Services';
$string['updatesuccess'] = 'Record has been updated successfully';
$string['notupdate'] = 'Record was not updated';
$string['emailnotsend'] = 'You have successfully save the certificate, but an email has not been sent!';
$string['emailsend'] = 'You have successfully sent the certificate';

$string['report/fullvaccination'] = 'Fully Vaccinated';
$string['report/partialvaccination'] = 'Partially Vaccinated';
$string['report/statusapproved'] = 'Fully Vaccinated';
$string['report/statusnotapproved'] = 'Not/Partially Vaccinated';
$string['report/statuspending'] = 'Pending';
$string['report/notsvailable'] = '-';

$string['reporthead/fullname'] = 'Full Name';
$string['reporthead/username'] = 'Student ID';
$string['reporthead/vaccinationcertificate'] = 'Certificate';
$string['reporthead/type'] = 'Vaccination Status';
$string['reporthead/message'] = 'Outcome';
$string['reporthead/date_submit'] = 'Date Submitted';
$string['reporthead/approved'] = 'Vaccination Status';
$string['reporthead/edit'] = 'Edit';
$string['reporthead/date_approved'] = 'Approved Date';
$string['reporthead/approvedby_user_id'] = 'Approved By';

$string['report/edit'] = 'Edit';
$string['report/view'] = 'View Here';
$string['report/notavailable'] = '-';


$string['email/emailtitle'] = 'Certificate Upload by user';
$string['email/sentby']     = 'By';

$string['covid_notifications_err_nocapability'] = 'You don\'t have permission to do that...';
$string['covid_notifications_invalid_record'] = 'Record  don\'t exist...';
$string['covid_notifications_invalid_user_record'] = 'User record  don\'t exist...';
$string['covid_main_class'] = 'main';
$string['covid_more_details_class'] = 'moredetails';
$string['covid_btn_prim_class'] = 'btn btn-primary';
$string['covid_add_icon_class'] = 'added-icon';
$string['database/username'] = 'username';
$string['database/fullname'] = 'fullname';
$string['database/vaccinationcertificate'] = 'vaccinationcertificate';
$string['database/message'] = 'message';
$string['database/date_submit'] = 'date_submit';
$string['database/approved'] = 'approved';
$string['database/date_approved'] = 'date_approved';
$string['database/approvedby_user_id'] = 'approvedby_user_id';
$string['database/edit'] = 'edit';
$string['table/class'] = 'admintable generaltable';
$string['filenotexisterror'] = 'File not available.';
$string['down'] = 'down';
$string['up'] = 'up';
$string['savechanges'] = 'Save changes';



// Privacy API.
$string['privacy:metadata:block_covid_notifications'] = 'Information about covid notifications the user has been exposed to and recorded interactions.';
$string['privacy:metadata:block_covid_notifications:user_id'] = 'The ID of the user that has seen/dismissed the notification.';
$string['privacy:metadata:block_covid_notifications:certificateid'] = 'The forum stores file id which have been uploaded by the user to form part of a forum post (any) .';
$string['privacy:metadata:block_covid_notifications:message'] = 'The body/message of the covid notification.';
$string['privacy:metadata:block_covid_notifications:type'] = 'Flag of whether define type of Vaccination  (1 = fully 2 = partially ).';
$string['privacy:metadata:block_covid_notifications:approved'] = 'Flag of whether define user is approved for fully vaccination  (1 = approved 2 = not approved).';
$string['privacy:metadata:block_covid_notifications:date_submit'] = 'On what date does the user submit the certificate.';
$string['privacy:metadata:block_covid_notifications:date_approved'] = 'On what date does the user is approved (Fully  Or partially vaccinated).';
$string['privacy:metadata:block_covid_notifications:approvedby_user_id'] = 'The ID of the user that approved the vaccination certificate (selected from admin)';
$string['privacy:metadata:block_covid_notifications:timeupdated'] = 'On what date does the records is updated';
$string['privacy:metadata:block_covid_notifications:vaccinationcertificate'] = 'The forum stores file which have been uploaded by the user to form part of a forum post';





