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
namespace block_covid_notifications\forms;
defined('MOODLE_INTERNAL') || die();
class upload_covidcertificate_form extends \moodleform {
    /**
     * Form definition
     *
     * @throws \coding_exception
     */
    public function definition() {
        global $CFG, $USER;
        $mform = $this->_form;
        $maxbytes = "";
        $mform->addElement('header', 'settingsheader', get_string('upload'));
        $fileexcept = array('.jpg', 'document');
        $options = array('subdirs' => 0,
            'maxbytes' => $maxbytes,
            'maxfiles' => 1,
            'accepted_types' => $fileexcept,
        );
        $mform->addElement(
                    'filemanager',
                    'vaccinationcertificate',
                    get_string("form/certicateupload",
                    "block_covid_notifications"),
                    null,
                    $options
                );
        $mform->addRule('vaccinationcertificate', null, 'required');

        $buttonarray = array();
        $buttonarray[] = $mform->createElement(
                                            'submit',
                                            'submitbutton',
                                            get_string('uploadcertificatebtn', 'block_covid_notifications')
                                        );
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }
}
