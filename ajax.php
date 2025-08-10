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
 * Toggles the messaging state for the course and updates the UI accordingly.
 *
 * This module updates the icons, text, and visibility of certain interface elements
 * based on whether messaging is enabled or disabled for the current course.
 * It also sends an AJAX request to persist the change on the server.
 *
 * @module     block_mensagenscurso/mensagenscurso
 * @copyright  2025 Marcelo M. Almeida Júnior
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Garante que o usuário está autenticado no Moodle.
require_login();

// Obtém parâmetros obrigatórios da requisição: ID do curso e novo estado (0 ou 1).
$courseid = required_param('courseid', PARAM_INT);
$state = required_param('state', PARAM_INT);

// Obtém o contexto do curso para verificar permissões.
$context = context_course::instance($courseid);

// Verifica se o usuário tem permissão para gerenciar atividades no curso.
require_capability('moodle/course:manageactivities', $context);

// Chave de configuração exclusiva para este curso e bloco.
$key = "course_{$courseid}";

// Salva o novo estado no banco de dados de configurações do Moodle para este bloco.
set_config($key, $state, 'block_mensagenscurso');

// Retorna resposta em JSON para o frontend.
echo json_encode(['status' => 'ok']);
