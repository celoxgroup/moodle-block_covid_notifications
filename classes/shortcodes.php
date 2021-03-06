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
 * Shortcodes handler.
 *
 * @package    block_covid_notifications
 * @copyright  2021 onwards Celox Group
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Celox Group <info@celoxgroup.com.au>
 */

namespace block_covid_notifications;

defined('MOODLE_INTERNAL') || die();

/**
 * Shortcodes handler.
 *
 * @package    block_covid_notifications
 * @copyright  2021 onwards Celox Group
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Celox Group <info@celoxgroup.com.au>
 */
class shortcodes
{

    /**
     * Handle shortcodes.
     *
     * @param string $shortcode The shortcode.
     * @param object $args The arguments of the code.
     * @param string|null $content The content, if the shortcode wraps content.
     * @param object $env The filter environment (contains context, noclean and originalformat).
     * @param Closure $next The function to pass the content through to process sub shortcodes.
     * @return string The new content.
     */

    public static function handle($shortcode, $args, $content, $env, $next) {
        global $USER;
        if ($shortcode === 'userlastname') {
            return $USER->lastname;
        } else if ($shortcode === 'userfirstname') {
            return $USER->firstname;
        } else if ($shortcode === 'userfullname') {
            return fullname($USER);
        } else if ($shortcode === 'useremail') {
            return $USER->email;
        } else if ($shortcode === 'username') {
            return $USER->username;
        }
        return $next($content);
    }

}
