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
			sets: {
				default: {
					language: LANGUAGE,
					plugins: 'pagebreak code autoresize',
					toolbar: 'undo redo | bold italic strikethrough underline blockquote | bullist numlist | removeformat pagebreak code',
					menubar: false,
					statusbar: false,
					pagebreak_separator: '<cut>'
				},
				light: {
					language: LANGUAGE,
					plugins: 'pagebreak code autoresize',
					toolbar: 'undo redo | bold italic strikethrough underline blockquote | bullist numlist | removeformat pagebreak code',
					menubar: false,
					statusbar: false,
					pagebreak_separator: '<cut>'
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
			this.element.tinymce( this.option( 'sets.' + this.option( 'set' ) ) );
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