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
 * Block for displaying notifications to users.
 *
 * @package    block_covid_notifications
 * @copyright  2021 onwards Celox Group
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Celox Group <info@celoxgroup.com.au>
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/blocks/covid_notifications/classes/covidcertificate.php');
/**
 * Class block_covid_notifications extends base blocks class. Initialise and render notifications.
 *
 * @copyright  2016 onwards LearningWorks Ltd {@link https://learningworks.co.nz/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_covid_notifications extends block_base
{
    /**
     * Initialise block, set title.
     */
    public function init() {
        $this->title = get_string('notifications', 'block_covid_notifications');
    }

    /**
     * Get and render content of block.
     *
     * @return  bool|stdClass|stdObject
     * @throws  dml_exception
     */
    public function get_content() {

        global $CFG, $USER, $DB;

        if ($this->content !== null) {
            return $this->content;
        }

        $pluginconfig = get_config('block_covid_notifications');
        $nottitle = !empty($pluginconfig->notificationtitle) ? $pluginconfig->notificationtitle : "";
        $notenable = !empty($pluginconfig->notificationtitleenable) ? $pluginconfig->notificationtitleenable : "";

        $guideline = new block_covid_notifications_handel();
        $touser = $guideline->getuseremailbyroleid($pluginconfig->approvedrole);
        $managerallow = is_siteadmin() || in_array($USER->email, $touser) ? true : false;
        if ($managerallow) {
            $redirecturl = new moodle_url('/blocks/covid_notifications/pages/admin_report.php');
            if (!is_siteadmin()) {
                $redirecturl = new moodle_url('/blocks/covid_notifications/pages/view_certificate_report.php');
            }
            $this->content = new stdClass();
            $renderer = $this->page->get_renderer('block_covid_notifications');
            $html = "";
            if (!empty($notenable)) {
                $html .= html_writer::start_tag('h5',
                array('class' => get_string('covid_main_class', 'block_covid_notifications')));
                $html .= $nottitle;
                $html .= html_writer::end_tag('h5');
            }

            $html .= html_writer::start_tag('div',
            array('class' => get_string('covid_more_details_class', 'block_covid_notifications')));
            $html .= html_writer::link(
                $redirecturl,
                get_string('clikaminreport', 'block_covid_notifications'),
                array('title' => get_string('clikaminreport', 'block_covid_notifications'),
                'class' => get_string('covid_btn_prim_class', 'block_covid_notifications')));
            $html .= html_writer::end_tag('div');
            $this->content->text = $html;
            return $this->content;
        } else {
            $record = $DB->get_record('block_covid_notifications', array('user_id' => $USER->id), '*');
            if ($record->type == 2) {
                $this->content->text = '';
                return $this->content;
            } else if (get_config('block_covid_notifications', 'enable')) {
                $this->content = new stdClass();
                // Get the renderer for this page.
                $renderer = $this->page->get_renderer('block_covid_notifications');
                $retype = "";
                if (!empty($record) && $record->type == 0) {
                    $pluginconfig->covidmessage = $pluginconfig->covidpendingmessage;
                    $pluginconfig->notificationtype = strtolower( get_string('notifications_add_option_success',
                    'block_covid_notifications'));
                    $retype = strtolower( get_string('report/statuspending',
                    'block_covid_notifications'));
                }
                if (!empty($record) && $record->type == 1) {
                    $managermessage = !empty($record->message) ? ' :<br/> ' . $record->message : "";
                    $pluginconfig->covidmessage = $pluginconfig->covidnotapprovemessage . $managermessage;
                    $pluginconfig->notificationtype = strtolower( get_string('notifications_add_option_warning',
                    'block_covid_notifications'));
                }
                // Render notifications.
                $html = "";
                if (!empty($notenable)) {
                    $html .= html_writer::start_tag('h4',
                    array('class' => get_string('covid_main_class', 'block_covid_notifications')));
                    $html .= $nottitle;
                    $html .= html_writer::end_tag('h4');
                }
                $html .= format_text($renderer->rendernotification($pluginconfig, $retype), FORMAT_HTML, array('filter' => true));
                $this->content->text = $html;
                return $this->content;
            }
        }

        return false;
    }

    /**
     * Gets Javascript that's required by the plugin.
     */
    public function get_required_javascript() {
        parent::get_required_javascript();
        $this->page->requires->js_call_amd('block_covid_notifications/notif', 'initialise');
    }

    /**
     * Allow multiple instances of the block throughout the site.
     *
     * @return bool
     */
    public function instance_allow_multiple() {
        // Are you going to allow multiple instances of each block?
        // If yes, then it is assumed that the block WILL use per-instance configuration.
        return true;
    }

    /**
     * HTML attributes such as 'class' or 'title' can be injected into the block.
     *
     * @return array
     */
    public function html_attributes() {
        $attributes = parent::html_attributes();

        if (!empty($this->config->class)) {
            $attributes['class'] .= ' ' . $this->config->class;
        }

        return $attributes;
    }
    /**
     * Specifies that block has global configurations/admin settings
     *
     * @return bool
     */
    public function has_config() {
        return true;
    }
    /**
     * Default return is false - header will be shown. Added check to show heading only if editing.
     *
     * @return boolean
     */
    public function hide_header() {
        // If editing, show header.
        if ($this->page->user_is_editing()) {
            return false;
        }
        return true;
    }
}
