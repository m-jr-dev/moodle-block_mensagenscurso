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
 * Toggles the messaging state for the course and persists it via AJAX.
 *
 * @package    block_mensagenscurso
 * @copyright  2025 Marcelo M. Almeida Júnior
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

// Ensures the user is authenticated in Moodle.
require_login();

// Gets required request parameters: course id and new state (0 or 1).
$courseid = required_param('courseid', PARAM_INT);
$state = required_param('state', PARAM_INT);

// Gets the course context to check permissions.
$context = context_course::instance($courseid);

// Checks if the user has permission to manage activities in the course.
require_capability('moodle/course:manageactivities', $context);

// Unique config key for this course and block.
$key = "course_{$courseid}";

// Stores the new state in Moodle configuration for this block.
set_config($key, $state, 'block_mensagenscurso');

// Returns JSON response to the frontend.
echo json_encode(['status' => 'ok']);
