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

defined('MOODLE_INTERNAL') || die();

/**
 * Mensagens por Curso block class.
 *
 * Allows toggling messaging capabilities between participants in a course or activity.
 *
 * @package    block_mensagenscurso
 * @copyright  2025 Marcelo M. Almeida Jr.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

class block_mensagenscurso extends block_base {
	
    /**
     * Inicializa o título do bloco.
     *
     * @return void
     */
    public function init() {
        $this->title = get_string('mensagenscurso', 'block_mensagenscurso');
    }
	
    /**
     * Define onde este bloco pode ser adicionado.
     *
     * @return array Formatos permitidos para o bloco.
     */
    public function applicable_formats() {
        return [
            'course'    => true,
            'mod'       => true,
            'mod-quiz'  => true,
            'mod-forum' => true,
        ];
    }

    /**
     * Indica se múltiplas instâncias do bloco são permitidas.
     *
     * @return bool Falso para permitir apenas uma instância por contexto.
     */
    public function instance_allow_multiple() {
        return false;
    }

    /**
     * Gera o conteúdo do bloco.
     *
     * @return stdClass Objeto contendo o HTML e requisitos de JS/CSS.
     */
    public function get_content() {
        global $PAGE, $CFG;
		
		// Se o conteúdo já foi gerado, retorna-o em cache.
        if ($this->content !== null) {
            return $this->content;
        }

		// Exibe apenas em contextos de curso ou módulo.
        if (!in_array($PAGE->context->contextlevel, [CONTEXT_COURSE, CONTEXT_MODULE])) {
            $this->content = new stdClass();
            $this->content->text = '';
            return $this->content;
        }

        // Obtém o ID do curso e estado salvo na configuração.
        $courseid = $PAGE->course->id;
        $configkey = "course_{$courseid}";
        $state = get_config('block_mensagenscurso', $configkey);
        $active = ($state !== '0') ? 1 : 0;

        // URLs absolutas dos ícones ON/OFF dentro do diretório pix do plugin.
        $icononurl = (new moodle_url('/blocks/mensagenscurso/pix/toggle_on.svg'))->out(false);
        $iconoffurl = (new moodle_url('/blocks/mensagenscurso/pix/toggle_off.svg'))->out(false);

        // Somente usuários com capacidade de adicionar instância veem o controle.
        if (has_capability('block/mensagenscurso:addinstance', $PAGE->context)) {
			// Texto de status e cor conforme estado ativo/inativo.
            $statustext = $active ? 'Ativo' : 'Inativo';
            $color = $active ? '#273a7c' : '#b3b3b3';

            // Botão de toggle com SVG e texto de status.
            $button = html_writer::tag('button', 
                html_writer::empty_tag('img', [
                    'src' => $active ? $icononurl : $iconoffurl,
                    'id' => 'msg-toggle-icon',
                    'style' => 'width:45px; height:24px; margin-right:8px; vertical-align:middle;',
                    'alt' => $statustext
                ]) 
                . html_writer::tag('span', $statustext, [
                    'id' => 'msg-toggle-text',
                    'style' => "color: {$color}; font-weight: bold;"
                ]),
                [
                    'id' => 'messaging-toggle',
                    'style' => 'cursor:pointer; border:none; background:none; font-size:16px; display:flex; align-items:center; padding:0;'
                ]
            );
            
			// Descrição explicativa abaixo do botão.
            $description = html_writer::tag('div',
                'Permitir a troca de mensagens entre os participantes.',
                [
                    'style' => "margin-top: 8px; font-size: 14px; color: #555!umportant;"
                ]
            );

			// Monta o conteúdo do bloco.
            $this->content = new stdClass();
            $this->content->text = $button . $description;
        } else {
			// Usuários sem permissão veem o bloco vazio.
            $this->content = new stdClass();
            $this->content->text = '';
        }

        // Carrega o JavaScript AMD para controle do toggle.
		$PAGE->requires->js_call_amd(
		'block_mensagenscurso/mensagenscurso',
		'init',
		[
			$active,
			$icononurl,
			$iconoffurl,
			$courseid,
			$CFG->wwwroot
		]
	);
		// Carrega o CSS específico do plugin.
		$PAGE->requires->css(new moodle_url('/blocks/mensagenscurso/styles.css'));

        return $this->content;
    }
}
