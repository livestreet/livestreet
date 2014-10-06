/**
 * Visual editor
 *
 * @module ls/editor/visual
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
	"use strict";

	$.widget( "livestreet.lsEditorVisual", {
		/**
		 * Дефолтные опции
		 */
		options: {
			set: 'default',
			sets: {
				common: {
					language: LANGUAGE,
					plugins: 'media table fullscreen autolink link pagebreak code autoresize emoticons',
					external_plugins: {
						// 'lsuser': PATH_SKIN + '/components/editor/js/tinymce/plugins/lsuser/plugin.js'
					},
					// skin_url: PATH_SKIN + '/components/editor/js/tinymce/skins/livestreet/',
					menubar: false,
					statusbar: false,
					pagebreak_separator: '<cut>',
					// extended_valid_elements: 'user',
					// custom_elements: '~user'
				},
				default: {
					toolbar: 'undo redo | styleselect bold italic strikethrough underline blockquote table | bullist numlist | link unlink emoticons media | lsuser removeformat pagebreak code fullscreen',
				},
				light: {
					toolbar: 'undo redo | bold italic strikethrough underline blockquote | bullist numlist | removeformat pagebreak code',
				},
			}
		},

		/**
		 * Конструктор
		 *
		 * @constructor
		 * @private
		 */
		_create: function () {
			this.element.tinymce( $.extend( {}, this.option( 'sets.common' ), this.option( 'sets.' + this.option( 'set' ) ) ) );
		},

		/**
		 * Вставка текста
		 *
		 * @param {String} text Текст для вставки
		 */
		insert: function ( text ) {
			this.element.tinymce().insertContent( text );
		}
	});
})(jQuery);