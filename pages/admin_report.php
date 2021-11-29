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
 * This file is used to setting the block allover the site
 *
 * @package    block_covid_notifications
 * @copyright  2021 onwards Celox Group
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Celox Group <info@celoxgroup.com.au>
 */

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');

require_login();

$context = context_system::instance();

if (!has_capability('block/notifications:managenotifications', $context)) {
    throw new moodle_exception('covid_notifications_err_nocapability', 'block_covid_notifications');
}

if (!is_siteadmin()) {
    throw new moodle_exception('covid_notifications_err_nocapability', 'block_covid_notifications');
}

$url = new moodle_url('/blocks/covid_notifications/pages/admin_report.php');
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title(get_string('page/adminreport', "block_covid_notifications"));
$PAGE->set_heading(get_string('page/adminreport', 'block_covid_notifications'));
$PAGE->navbar->add(get_string('blocks'));
$PAGE->navbar->add(get_string('pluginname', 'block_covid_notifications'));
$PAGE->navbar->add(get_string('notifications_table_title_short', 'block_covid_notifications'));

$defaultsort = "ue.approved";
$sort = optional_param("sort", $defaultsort, PARAM_NOTAGS);
$dir  = optional_param("dir", "ASC", PARAM_ALPHA); // Sorting direction.

$dbusername = get_string('database/username', "block_covid_notifications");
$dbfullname = get_string('database/fullname', "block_covid_notifications");
$dbvaccincert = get_string('database/vaccinationcertificate', "block_covid_notifications");
$dbmessage = get_string('database/message', "block_covid_notifications");
$dbdatesub = get_string('database/date_submit', "block_covid_notifications");
$dbapprove = get_string('database/approved', "block_covid_notifications");
$dbdateapprov = get_string('database/date_approved', "block_covid_notifications");
$dbappoveser = get_string('database/approvedby_user_id', "block_covid_notifications");
$dbedit = get_string('database/edit', "block_covid_notifications");

$columns = array(
    $dbusername,
    $dbfullname,
    $dbvaccincert,
    $dbmessage,
    $dbdatesub,
    $dbapprove,
    $dbdateapprov,
    $dbappoveser,
    $dbedit,
);

$scolumns = [
    $dbusername => [$dbusername],
    $dbfullname => [$dbfullname],
    $dbvaccincert => [$dbvaccincert],
    $dbmessage => [$dbmessage],
    $dbdatesub => [$dbdatesub],
    $dbapprove => [$dbapprove],
    $dbdateapprov => [$dbdateapprov],
    $dbappoveser => [$dbappoveser],
    $dbedit => [$dbedit],
];
// Build array of all the possible sort columns.
$allsorts = [];
foreach ($scolumns as $sorts) {
    foreach ($sorts as $s) {
        $allsorts[] = $s;
    }
}
// Sanitize sort to ensure they are valid column sorts.
if (!in_array($sort, $allsorts, true)) {
    $sort = $defaultsort;
}
$params = [];

echo $OUTPUT->header();

$orderby = "ORDER BY $sort $dir";

$sql = "SELECT
    u.id,
    u.username,
    CONCAT(u.firstname, ' ', u.lastname) as fullname,
    ue.vaccinationcertificate,
    ue.type,
    ue.message,
    ue.date_submit,
    ue.approved,
    ue.date_approved,
    ue.approvedby_user_id,
    ue.id as edit
    FROM {block_covid_notifications} ue
    JOIN {user} u ON u.id = ue.user_id
    WHERE u.id = ue.user_id $orderby";

$records = $DB->get_records_sql($sql, $params);

$baseurl = new moodle_url($url, [
    "sort" => $sort,
    "dir" => $dir,
]);

$hcolumns = array();

// Foreach column we look at it's applicable sort columns and build a final link header.
foreach ($columns as $column) {
    foreach ($scolumns[$column] as $sortcolumn) {
        if ($sort != $sortcolumn) {
            $cdir = $dir;
            $cicon = "";
        } else {
            $cdir = $dir == "ASC" ? "DESC" : "ASC";
            $cicondir = ($dir == "ASC") ? "down" : "up";
            $cicon = $OUTPUT->pix_icon('t/' . $cicondir, get_string($cicondir));
        }
        // Get a string for this sort link.
        $columnheader = get_string("reporthead/$sortcolumn", 'block_covid_notifications');
        // Update parameters for sort and direction for this column in the final url.
        $baseurl->param('sort', $sortcolumn);
        $baseurl->param('dir', $cdir);
        $hcolumns[$column] = "<a href=$baseurl#table>$columnheader</a>$cicon";
    }
}
$table = new html_table();
$table->head = $hcolumns;
$table->attributes["class"] = get_string('table/class', "block_covid_notifications");
$table->data = [];
$assignmentscale = null;
$coursetotalscale = null;
$coursetotalid = null;

foreach ($records as $record) {

    if ($record->type == 2) {
        $aprv = get_string('report/statusapproved', "block_covid_notifications");
        $record->approved = "<span style='color: Green;'>
                                <i class='icon fa fa-check-circle fa-fw' aria-hidden='true' title='$aprv'aria-label='$aprv'>
                                </i>
                            </span>";
    } else if ($record->type == 1) {
        $na = get_string('report/statusnotapproved', "block_covid_notifications");
        $record->approved = "<span style='color: Red;'>
                            <i class='icon fa fa-times-circle fa-fw' aria-hidden='true' title='$na' aria-label='$na'></i>
                            </span>";
    } else {
        $pen = get_string('report/statuspending', "block_covid_notifications");
        $record->approved = "<span style='color: Orange;'>
                            <i class='icon fa fa-question-circle fa-fw' aria-hidden='true' title='$pen' aria-label='$pen'></i>
                            </span>";
    }
    $message = !empty($record->message) ? $record->message : get_string('report/notsvailable', "block_covid_notifications");
    $userfullname = !empty($record->fullname) ? $record->fullname : get_string(
                                                                    'report/notsvailable',
                                                                    "block_covid_notifications");
    $approvedbybyuser = !empty($record->approvedby_user_id) ? $record->approvedby_user_id : get_string(
                                                                                            'report/notsvailable',
                                                                                            "block_covid_notifications");
    if ($record->approvedby_user_id) {
        $approvedbybyuser = $DB->get_record('user', array('id' => $approvedbybyuser), '*');
        $approvedbybyuser = $approvedbybyuser->username;
    }

    $curl = !empty($record->vaccinationcertificate) ? new moodle_url($record->vaccinationcertificate) : "";
    $imageurl = !empty($curl) ? '<a href="' . $curl . '" target="_blank">
                                <i class="icon fa fa-search-plus fa-fw " aria-hidden="true"
                                title="View Certificate" aria-label="View Certificate"></i>
                                </a>' : '<i class="icon fa fa-search-minus fa-fw "></i>';
    $editurl = new moodle_url('/blocks/covid_notifications/pages/update_records.php',
    array('id' => $record->edit, 'sesskey' => sesskey()));
    $record->user_id = !empty($record->user_id) ? $record->user_id : "";
    $record->fullname = $userfullname;
    $record->vaccinationcertificate = $imageurl;
    $record->message = $message;
    $record->date_submit = !empty($record->date_submit) ? $record->date_submit : null;
    $record->approved = $record->approved;
    $record->date_approved = !empty($record->date_approved) ? $record->date_approved : null;
    $record->approvedby_user_id = $approvedbybyuser;
    $record->edit = '<a href="' . $editurl . '">'.  get_string('report/edit', "block_covid_notifications") .'</a>';
    $final = new stdClass();
    foreach ($columns as $column) {
        if ($column === 'date_submit' && !empty($record->{$column})) {
            $dt = DateTime::createFromFormat("U", $record->{$column}, core_date::get_user_timezone_object());
            $final->{$column} = $dt ? $dt->format('d/m/Y') : '';
        } else if ($column === 'date_approved' && !empty($record->{$column})) {
            $dt = DateTime::createFromFormat("U", $record->{$column}, core_date::get_user_timezone_object());
            $final->{$column} = $dt ? $dt->format('d/m/Y') : '';
        } else {
            $final->{$column} = $record->{$column};
        }
    }
    $table->data[] = $final;
}
echo html_writer::table($table);
echo $OUTPUT->footer();
