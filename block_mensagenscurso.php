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
 * Mensagens por Curso block class.
 *
 * Allows toggling messaging capabilities between participants in a course or activity.
 *
 * @package    block_mensagenscurso
 * @copyright  2025 Marcelo M. Almeida Jr.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Block class for block_mensagenscurso.
 *
 * @package    block_mensagenscurso
 * @copyright  2025 Marcelo M. Almeida Jr.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_mensagenscurso extends block_base {

    /**
     * Initialises the block title.
     *
     * @return void
     */
    public function init() {
        $this->title = get_string('mensagenscurso', 'block_mensagenscurso');
    }

    /**
     * Defines where this block can be added.
     *
     * @return array Allowed formats for the block.
     */
    public function applicable_formats() {
        return [
            'course' => true,
            'mod' => true,
            'mod-quiz' => true,
            'mod-forum' => true,
        ];
    }

    /**
     * Indicates whether multiple instances of the block are allowed.
     *
     * @return bool False to allow only one instance per context.
     */
    public function instance_allow_multiple() {
        return false;
    }

    /**
     * Generates the block content.
     *
     * @return stdClass Object containing the HTML and JS/CSS requirements.
     */
    public function get_content() {
        $page = $this->page;
        global $CFG;

        // If the content has already been generated, returns it from cache.
        if ($this->content !== null) {
            return $this->content;
        }

        // Displays only in course or module contexts.
        if (!in_array($page->context->contextlevel, [CONTEXT_COURSE, CONTEXT_MODULE])) {
            $this->content = new stdClass();
            $this->content->text = '';
            return $this->content;
        }

        // Gets the course id and state saved in configuration.
        $courseid = $page->course->id;
        $configkey = "course_{$courseid}";
        $state = get_config('block_mensagenscurso', $configkey);
        $active = ($state !== '0') ? 1 : 0;

        // Absolute URLs for ON/OFF icons within the plugin pix directory.
        $icononurl = (new moodle_url('/blocks/mensagenscurso/pix/toggle_on.svg'))->out(false);
        $iconoffurl = (new moodle_url('/blocks/mensagenscurso/pix/toggle_off.svg'))->out(false);

        // Only users with addinstance capability see the control.
        if (has_capability('block/mensagenscurso:addinstance', $page->context)) {
            // Status text and colour according to active/inactive state.
            $statustext = $active ? 'Ativo' : 'Inativo';
            $color = $active ? '#273a7c' : '#b3b3b3';

            // Toggle button with SVG and status text.
            $button = html_writer::tag(
                'button',
                html_writer::empty_tag('img', [
                    'src' => $active ? $icononurl : $iconoffurl,
                    'id' => 'msg-toggle-icon',
                    'style' => 'width:45px; height:24px; margin-right:8px; vertical-align:middle;',
                    'alt' => $statustext,
                ])
                . html_writer::tag('span', $statustext, [
                    'id' => 'msg-toggle-text',
                    'style' => "color: {$color}; font-weight: bold;",
                ]),
                [
                    'id' => 'messaging-toggle',
                    'style' => 'cursor:pointer; border:none; background:none; font-size:16px;'
                        . ' display:flex; align-items:center; padding:0;',
                ]
            );

            // Explanatory description below the button.
            $description = html_writer::tag(
                'div',
                'Permitir a troca de mensagens entre os participantes.',
                [
                    'style' => 'margin-top: 8px; font-size: 14px; color: #555 !important;',
                ]
            );

            // Builds the block content.
            $this->content = new stdClass();
            $this->content->text = $button . $description;
        } else {
            // Users without permission see an empty block.
            $this->content = new stdClass();
            $this->content->text = '';
        }

        // Loads the AMD JavaScript for toggle control.
        $page->requires->js_call_amd(
            'block_mensagenscurso/mensagenscurso',
            'init',
            [
                $active,
                $icononurl,
                $iconoffurl,
                $courseid,
                $CFG->wwwroot,
            ]
        );

        // Loads the plugin-specific CSS.
        $page->requires->css(new moodle_url('/blocks/mensagenscurso/styles.css'));

        return $this->content;
    }
}
