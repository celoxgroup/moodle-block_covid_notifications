<?php
// This file is part of the Contact Form plugin for Moodle - http://moodle.org/
//
// Contact Form is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Contact Form is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Contact Form.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This plugin for Moodle is used to send emails through a web form.
 *
 * @package    block_covid_notifications
 * @copyright  2021 onwards Celox Group
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Celox Group <info@celoxgroup.com.au>
 */

defined('MOODLE_INTERNAL') || die();

class block_covid_notifications_handel
{

    public $certificatetable = 'block_covid_notifications';
    public function covid_get_users_by_id($userids) {
        global $DB;
        return $result = $DB->get_records_list('user', 'id', $userids);
    }
    public function getusercertificatereport($id = 0) {
        global $DB, $USER, $CFG;
        $guideline = $DB->get_record($this->certificatetable, array('id' => $id));
        return $guideline;
    }

    public function insertguideline($data) {
        global $DB, $USER, $CFG;
        $guidelineid = $DB->insert_record($this->certificatetable, $data);
        return $guidelineid;
    }
    public function updateguideline($data) {
        global $DB, $USER, $CFG;
        $guidelineid = $DB->update_record($this->certificatetable, $data);
        return $guidelineid;
    }
    public function getuseremailbyroleid($rolid = '') {
        global $DB, $USER, $CFG;
        $response = [];
        if (empty($rolid)) {
            return $response;
        }
        $params = [$rolid];
        $sql = "SELECT u.email AS email
        FROM {user} u
        INNER JOIN {role_assignments} ra ON ra.userid = u.id
        INNER JOIN {role} r ON r.id = ra.roleid
        WHERE u.deleted = 0 AND ra.roleid IN (?)  GROUP BY u.id";
        $records = $DB->get_records_sql($sql, $params);
        $resonse = [];
        foreach ($records as $singlerecord) {
            $resonse[] = $singlerecord->email;
        }
        return $resonse;
    }

    public function getuserbyselectedid($rolid = '') {
        global $DB, $USER, $CFG;
        $response = [];
        if (empty($rolid)) {
            return $response;
        }
        $params = [$rolid];
        $sql = "SELECT u.id AS id
          FROM {user} u
          INNER JOIN {role_assignments} ra ON ra.userid = u.id
          INNER JOIN {role} r ON r.id = ra.roleid
          WHERE u.deleted = 0 AND ra.roleid IN (?) GROUP BY u.id";
        $records = $DB->get_records_sql($sql, $params);
        $users = [];
        foreach ($records as $singlerecord) {
            $users[] = $singlerecord->id;
        }
        $resonse = $this->covid_get_users_by_id($users);
        return $resonse;
    }

    public function getimagefullurl($vaccinid = "", $contextid = "") {
        global $CFG;
        $covidcertificate = "";
        if (empty($vaccinid)) {
            return $covidcertificate;
        }
        $covidcertificatedraftitemid = file_get_submitted_draft_itemid('vaccinationcertificate');
        file_save_draft_area_files($vaccinid, $contextid, 'block_covid_notifications', 'attachment',
            $covidcertificatedraftitemid, array('subdirs' => 0, 'maxbytes' => $CFG->maxbytes, 'maxfiles' => 50));
        $fs = get_file_storage();
        $reffiles = $fs->get_area_files($contextid, 'block_covid_notifications', 'attachment', false, 'itemid', false);
        if (!empty($reffiles)) {
            foreach ($reffiles as $key => $files) {
                if ($covidcertificatedraftitemid == $files->get_itemid()) {
                    $filename = $files->get_filename();
                    $covidcertificate = '/blocks/covid_notifications/covid_plugin_file.php/' . $files->get_contextid() .
                                        '/block_covid_notifications/attachment/'
                                        . $files->get_itemid() . "/"
                                        . $filename;
                }
            }
        }
        return $covidcertificate;
    }

}
