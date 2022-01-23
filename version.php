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
 * Block version details
 *
 * @package    block_covid_notifications
 * @copyright  2021 onwards Celox Group
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Celox Group <info@celoxgroup.com.au>
 **/

defined('MOODLE_INTERNAL') || die;
$plugin->component = 'block_covid_notifications'; // Recommended since 2.0.2 (MDL-26035). Required since 3.0 (MDL-48494).
$plugin->version = 2021122710; // YYYYMMDDHH (year, month, day, 24-hr format hour).
$plugin->requires = 2018051712; // YYYYMMDDHH (Version number for Moodle v3.10 as at 09/11/2020).
$plugin->maturity = MATURITY_STABLE; // Code maturity/stability.
$plugin->release = 'v2.1.3'; // Human-readable release version.
