/**
 * Вспомгательные функции для текстового редактора
 * 
 * @module ls/editor
 * 
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

ls.editor = (function($) {
	"use strict";

	/**
	 * Инициализация
	 */
	this.init = function(selector) {
		var _this = this;

		$(selector).each(function () {
			var editor = $(this),
				type = editor.data('editor-type'),
				set = editor.data('editor-set') || 'default';

			ls.editor[type].init(editor, set);
		})
	};

	return this;
}).call(ls.editor || {},jQuery);