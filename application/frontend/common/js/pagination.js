/**
 * Пагинация
 *
 * @module ls/pagination
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
	"use strict";

	$.widget( "livestreet.lsPagination", {
		/**
		 * Дефолтные опции
		 */
		options: {
			keys: {
				// Комбинация клавиш для перехода на следующую страницу
				next: 'ctrl+right',

				// Комбинация клавиш для перехода на предыдущую страницу
				prev: 'ctrl+left'
			},

			// Селекторы
			selectors: {
				next: '.js-pagination-next',
				prev: '.js-pagination-prev'
			},

			// Хэш добавляемый к url при переходе на страницу
			hash: {
				next: '',
				prev: ''
			}
		},

		/**
		 * Конструктор
		 *
		 * @constructor
		 * @private
		 */
		_create: function () {
			this.document = $(document);

			this.next = this.element.find(this.options.selectors.next);
			this.prev = this.element.find(this.options.selectors.prev);

			this.document.bind('keydown', this.options.keys.next, this.goNext.bind(this));
			this.document.bind('keydown', this.options.keys.prev, this.goPrev.bind(this));
		},

		/**
		 * Переход на следующую страницу
		 */
		goNext: function () {
			if ( this.next.length ) window.location = this.next.attr('href') + ( this.options.hash.next ? '#' + this.options.hash.next : '' );
		},

		/**
		 * Переход на предыдущую страницу
		 */
		goPrev: function () {
			if ( this.prev.length ) window.location = this.prev.attr('href') + ( this.options.hash.prev ? '#' + this.options.hash.prev : '' );
		}
	});
})(jQuery);