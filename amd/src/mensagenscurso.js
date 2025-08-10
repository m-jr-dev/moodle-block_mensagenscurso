/*
 * This file is part of Moodle - http://moodle.org/
 *
 * Moodle is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Moodle is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Controls the display of message interface elements (icon, text, description, popover,
 * user button, and button group), toggling between active/inactive states based on configuration.
 * Also handles the toggle button click to change state and send an AJAX update.
 *
 * @module     block_mensagenscurso/mensagenscurso
 * @copyright  2025 Marcelo M. Almeida Júnior
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define([], function() {
    return {
		
		/**
         * Inicializa o módulo de mensagens.
         *
         * @param {number} active      Estado inicial (1 = ativo, 0 = inativo).
         * @param {string} iconOnUrl   URL do ícone quando ativo.
         * @param {string} iconOffUrl  URL do ícone quando inativo.
         * @param {number} courseid    ID do curso atual.
         * @param {string} wwwroot     URL raiz do Moodle para chamadas AJAX.
         */
		
        init: function(active, iconOnUrl, iconOffUrl, courseid, wwwroot) {
			// Obtém referências aos elementos da interface
            var icon = document.getElementById('msg-toggle-icon');
            var text = document.getElementById('msg-toggle-text');
            var description = document.getElementById('msg-description');
            var pop = document.querySelector("div.popover-region[data-region='popover-region-messages']");
            var userbtn = document.querySelector('a#message-user-button');
            var btnGroup = document.querySelector('.btn-group.header-button-group.mx-3');
			/**
             * Aplica o estado ativo ou inativo aos elementos da interface.
             */
            function applyState() {
				// Atualiza o ícone conforme estado
                if (icon) {
                    icon.src = active ? iconOnUrl : iconOffUrl;
                    icon.alt = active ? 'Ativo' : 'Inativo';
                }				
				// Atualiza texto de status e estilo
                if (text) {
                    text.textContent = active ? 'Ativo' : 'Inativo';
                    text.style.color = active ? '#273a7c' : '#b3b3b3';
                    text.style.fontWeight = 'bold';
                }				
				// Atualiza cor da descrição
                if (description) {
                    description.style.color = active ? '#273a7c' : '#b3b3b3';
                }				
				// Exibe ou oculta elementos conforme estado
                if (pop)      pop.style.display = active ? '' : 'none';
                if (userbtn)  userbtn.style.display = active ? '' : 'none';
                if (btnGroup) btnGroup.style.display = active ? '' : 'none';
            }
			// Aplica o estado inicial ao carregar o módulo
            applyState();

			// Configura listener para o botão de toggle
            var toggle = document.getElementById('messaging-toggle');
            if (toggle) {
                toggle.addEventListener('click', function() {
					 // Inverte o estado ativo/inativo
                    active = active ? 0 : 1;
					// Reaplica o novo estado aos elementos
                    applyState();

					// Envia requisição AJAX para atualizar estado no servidor
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', wwwroot + '/blocks/mensagenscurso/ajax.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.send('courseid=' + courseid + '&state=' + active);
                });
            }
        }
    };
});
