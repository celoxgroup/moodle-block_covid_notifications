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
class update_covidcertificate_form extends \moodleform {
    /**
     * Form definition
     *
     * @throws \coding_exception
     */
    public function definition() {
        global $CFG, $USER;
        $mform = $this->_form;

        $id = $this->_customdata['id'];
        $retype = $this->_customdata['retype'];
        $remessage = $this->_customdata['remessage'];
        $curl = $this->_customdata['curl'];

        $mform->addElement('hidden', 'id', $id);
        $mform->setType('id', PARAM_INT);
        if (!empty($curl)) {
            $htmllink = '<div class="form-group row" >
            <div class="col-md-3">' . get_string("form/download", "block_covid_notifications") . '</div>
            <div class="col-md-9 form-inline felement" data-fieldtype="group">
            <a href="' . $curl . '" target="_blank">' . get_string("form/cliktodownload", "block_covid_notifications") . '</a>
            </div>
            </div>';
            $mform->addElement('html', $htmllink);
        }

        $radioarray = array();
        $radioarray[] = $mform->createElement('radio', 'type', '', get_string('yes'), 2);
        $radioarray[] = $mform->createElement('radio', 'type', '', get_string('no'), 1);
        $mform->addGroup($radioarray, 'radioar', get_string("form/vaccintype", "block_covid_notifications"), array(' '), false);
        $mform->setDefault('type', $retype);
        $mform->addElement(
                        'textarea',
                        'message',
                        get_string("form/vaccinmessage", "block_covid_notifications"),
                         'wrap="virtual" rows="20" cols="50"'
                        );
        $mform->setDefault('message', $remessage);
        $mform->setType('message', PARAM_CLEANHTML);

        $buttonarray = array();
        $buttonarray[] = $mform->createElement('submit', 'submitbutton', get_string("savechanges", "block_covid_notifications"));
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }

}
