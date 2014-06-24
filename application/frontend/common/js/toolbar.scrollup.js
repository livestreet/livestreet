/**
 * Прокрутка вверх
 *
 * @module ls/toolbar/scrollup
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
	"use strict";

	$.widget( "livestreet.lsToolbarScrollUp", {
		/**
		 * Дефолтные опции
		 */
		options: {
			// Продолжительность прокрутки, мс
			duration: 500
		},

		/**
		 * Конструктор
		 *
		 * @constructor
		 * @private
		 */
		_create: function () {
			this._on( { 'click': this.up } );
			this._on( this.window, { 'scroll': this.check } );
		},

		/**
		 * Показывает/скрывает кнопку прокрутки в зависимости от значения scrollTop
		 */
		check: function() {
			this.element[ this.window.scrollTop() > this.window.height() / 2 ? 'fadeIn' : 'fadeOut' ]( 500 );
		},

		/**
		 * Прокрутка вверх
		 */
		up: function() {
			$.scrollTo( 0, this.options.duration );
		}
	});
})(jQuery);