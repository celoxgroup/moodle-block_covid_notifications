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

$url = new moodle_url('/blocks/covid_notifications/pages/view_certificate_report.php');
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title(get_string('page/userreport', "block_covid_notifications"));
$PAGE->set_heading(get_string('page/userreport', 'block_covid_notifications'));
$PAGE->navbar->add(get_string('blocks'));
$PAGE->navbar->add(get_string('pluginname', 'block_covid_notifications'));
$PAGE->navbar->add(get_string('notifications_table_title_short', 'block_covid_notifications'));
$defaultsort = "ue.approved";
$sort = optional_param("sort", $defaultsort, PARAM_NOTAGS);
$dir = optional_param("dir", "ASC", PARAM_ALPHA); // Sorting direction.

$dbusername = get_string('database/username', "block_covid_notifications");
$dbfullname = get_string('database/fullname', "block_covid_notifications");
$dbvaccincert = get_string('database/vaccinationcertificate', "block_covid_notifications");
$dbmessage = get_string('database/message', "block_covid_notifications");
$dbdatesub = get_string('database/date_submit', "block_covid_notifications");
$dbapprove = get_string('database/approved', "block_covid_notifications");
$dbedit = get_string('database/edit', "block_covid_notifications");

$columns = array(
    $dbusername,
    $dbfullname,
    $dbvaccincert,
    $dbmessage,
    $dbdatesub,
    $dbapprove,
    $dbedit,
);

$scolumns = [
    $dbusername => [$dbusername],
    $dbfullname => [$dbfullname],
    $dbvaccincert => [$dbvaccincert],
    $dbmessage => [$dbmessage],
    $dbdatesub => [$dbdatesub],
    $dbapprove => [$dbapprove],
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

echo $OUTPUT->header();
$params = [$sort, $dir];
$sql = "SELECT
    u.id,
    u.username,
    CONCAT(u.firstname, ' ', u.lastname) as fullname,
    ue.vaccinationcertificate,
    ue.type,
    ue.message,
    ue.date_submit,
    ue.approved,
    ue.id as edit
    FROM {block_covid_notifications} ue
    JOIN {user} u ON u.id = ue.user_id
    WHERE u.id = ue.user_id ORDER BY ?, ?";

// Old cooment code echo html_writer::start_tag('ul'); end.
$records = $DB->get_records_sql($sql, $params);

$baseurl = new moodle_url($url, [
    "sort" => $sort,
    "dir" => $dir
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
            $cicondir = ($dir == "ASC") ? get_string('down', "block_covid_notifications")
            : get_string('up', "block_covid_notifications");
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
        $record->approved = get_string('report/statusapproved', "block_covid_notifications");
    } else if ($record->type == 1) {
        $record->approved = get_string('report/statusnotapproved', "block_covid_notifications");
    } else {
        $record->approved = get_string('report/statuspending', "block_covid_notifications");
    }
    $message = !empty($record->message) ? format_string($record->message)
    : get_string('report/notsvailable', "block_covid_notifications");
    $userfullname = !empty($record->fullname) ? $record->fullname : get_string('report/notsvailable', "block_covid_notifications");
    $curl = !empty($record->vaccinationcertificate) ? new moodle_url($record->vaccinationcertificate) : "";
    $imageurl = !empty($curl) ? '<a href="' . $curl . '" target="_blank">'.
    get_string('report/view', "block_covid_notifications") .'</a>'
    : get_string('report/notavailable', "block_covid_notifications");
    $editurl = new moodle_url('/blocks/covid_notifications/pages/update_records.php',
    array('id' => $record->edit, 'sesskey' => sesskey()));
    $record->user_id = $record->user_id;
    $record->fullname = $userfullname;
    $record->vaccinationcertificate = $imageurl;
    $record->message = $message;
    $record->date_submit = !empty($record->date_submit) ? $record->date_submit : null;
    $record->approved = $record->approved;
    $record->edit = '<a href="' . $editurl . '">'.  get_string('report/edit', "block_covid_notifications") . '</a>';

    $final = new stdClass();
    foreach ($columns as $column) {
        if ($column === 'date_submit' && !empty($record->{$column})) {
            $dt = DateTime::createFromFormat("U", $record->{$column}, core_date::get_user_timezone_object());
            $final->{$column} = $dt ? $dt->format('d/m/Y') : '';
        } else {
            $final->{$column} = $record->{$column};
        }
    }
    $table->data[] = $final;
}
echo html_writer::table($table);
// Old comment } end.
echo $OUTPUT->footer();
