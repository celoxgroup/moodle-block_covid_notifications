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
 * Advanced Notifications renderer - what gets displayed
 *
 * @package    block_covid_notifications
 * @copyright  2021 onwards Celox Group
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Celox Group <info@celoxgroup.com.au>
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Renders notifications.
 *
 * @copyright  2016 onwards LearningWorks Ltd {@link https://learningworks.co.nz/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_covid_notifications_renderer extends plugin_renderer_base
{
    /**
     * Renders notification on page.
     *
     * @param   array $notifications Attributes about notifications to render.
     * @return  string Returns HTML to render notification.
     */

    public function rendernotification($pluginconfig = [], $retype = "") {

        global $USER;

        $notificationalert = $pluginconfig->notificationtype;
        $notificationalertmessage = $pluginconfig->covidmessage;
        $notificationdismissible = $pluginconfig->dismissible;
        $notificationdismiss = 1;
        $notificationiconflag = $pluginconfig->icon;

        $aicon = '';
        // Allows for custom styling and serves as a basic filter if anything unwanted was somehow submitted.
        if (
            $notificationalert == strtolower(get_string('notifications_add_option_info_short', 'block_covid_notifications')) ||
            $notificationalert == strtolower(get_string('notifications_add_option_success', 'block_covid_notifications')) ||
            $notificationalert == strtolower(get_string('notifications_add_option_warning', 'block_covid_notifications')) ||
            $notificationalert == strtolower(get_string('notifications_add_option_danger', 'block_covid_notifications'))
            ) {
            $aicon = $notificationalert;
        } else {
            $notificationalert = ( $notificationalert == strtolower(
                get_string('notifications_add_option_announcement', 'block_covid_notifications'))) ? strtolower(
                    get_string('notifications_add_option_info_short', 'block_covid_notifications')
                    ). ' ' . strtolower(get_string('notifications_add_option_announcement', 'block_covid_notifications'))
                    : strtolower(get_string('notifications_add_option_info_short', 'block_covid_notifications'));

            $aicon = strtolower(get_string('notifications_add_option_info_short', 'block_covid_notifications'));
        }

        $extraclasses = ' ' . $notificationalert;
        if ($notificationdismissible == 1) {
            $extraclasses .= ' '. strtolower(get_string('setting/dismissible', 'block_covid_notifications'));
        }
        if ($notificationiconflag == 1) {
            $extraclasses .= ' '. strtolower(get_string('setting/aicon', 'block_covid_notifications'));
        }

        $html = '<div class="notification-block-wrapper' . $extraclasses .
            '" data-dismiss="' . $notificationdismiss .
            '"><div class="alert alert-' . $notificationalert . '" style="display:flex;">';

        $class = "";
        if (!empty($notificationiconflag) && $notificationiconflag == 1) {
            $html .= '<div class="cv-icone-left"><img class="notification_aicon" src="' .
            $this->image_url($aicon, 'block_covid_notifications') . '"></div>';
            $class = " added-icon";
        }

        $html .= '<div class="cv-notification-right' . $class . ' ">';

        if (!empty($notificationalertmessage)) {
            $html .= $notificationalertmessage;
        }

        // If dismissible, add close button.
        if ($notificationdismissible == 1) {
            $html .= '<div class="notification-block-close"><strong>&times;</strong></div>';
        }

        if ( $retype != strtolower(get_string('report/statuspending', 'block_covid_notifications')) ) {
            $html .= html_writer::start_tag('div', array('class' => 'moredetails'));
            $html .= html_writer::link(
                new moodle_url('/blocks/covid_notifications/pages/upload_covid_certificate.php'),
                get_string('clikupploadbutton', 'block_covid_notifications'),
                array('title' => get_string('clikupploadbutton', 'block_covid_notifications'), 'class' => "btn btn-primary"));
            $html .= html_writer::end_tag('div');
        }
        // Close notification block.
        $html .= '</div></div></div>';

        return $html;
    }

}
