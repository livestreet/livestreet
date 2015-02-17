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
			this._on({ click: 'onClick' });
			this._on( this.window, { scroll: 'onScroll' } );
		},

		/**
		 * Показывает/скрывает кнопку прокрутки в зависимости от значения scrollTop
		 */
		onScroll: function() {
			if ( this.prev && this.isTop && this.window.scrollTop() > 0 ) {
				this.element.removeClass( ls.options.classes.states.active );
				this.isTop = false;
				this.prev = null;
			}

			! this.prev && this.element[ this.window.scrollTop() > this.window.height() / 2 ? 'fadeIn' : 'fadeOut' ]( 500 );
		},

		/**
		 * Обработка клика
		 */
		onClick: function() {
			// Не обрабатываем клики в процессе скролла
			! this.isScroll && this[ this.prev && this.isTop ? 'back' : 'up' ]();
		},

		/**
		 * Прокрутка вверх
		 */
		up: function() {
			this.prev = this.window.scrollTop();
			this.isScroll = true;

			$.scrollTo( 0, this.options.duration, {
				onAfter: function () {
					this.isTop = true;
					this.isScroll = false;
					this.element.addClass( ls.options.classes.states.active );
				}.bind(this)
			});
		},

		/**
		 * Прокрутка к предыдущей позиции
		 */
		back: function() {
			if ( ! this.prev ) return;

			this.isTop = false;
			this.isScroll = true;

			$.scrollTo( this.prev, this.options.duration, {
				onAfter: function () {
					this.element.removeClass( ls.options.classes.states.active );
					this.isScroll = false;
					this.prev = null;
				}.bind(this)
			});
		}
	});
})(jQuery);