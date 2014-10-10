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
			this.elements = {
				next: this.element.find(this.options.selectors.next),
				prev: this.element.find(this.options.selectors.prev)
			};

			this.document.bind( 'keydown', this.options.keys.next, this.next.bind(this, false) );
			this.document.bind( 'keydown', this.options.keys.prev, this.prev.bind(this, false) );
		},

		/**
		 * Переход на страницу
		 *
		 * @param {String} name Название функции
		 */
		_go: function ( name ) {
			return function( useHash ) {
				if ( this.elements[ name ].length ) {
					window.location = this.elements[ name ].attr('href') + ( this.options.hash[ name ] && useHash ? '#' + this.options.hash[ name ] : '' );
				} else {
					ls.msg.error( null, name == 'next' ? ls.lang.get('pagination.notices.last') : ls.lang.get('pagination.notices.first') );
				}
			}
		},

		/**
		 * Переход на следующую страницу
		 *
		 * @param {Boolean} useHash Добавлять параметр gotopic в хэш или нет
		 */
		next: function ( useHash ) {
			return this._go( 'next' ).apply(this, arguments);
		},

		/**
		 * Переход на предыдущую страницу
		 *
		 * @param {Boolean} useHash Добавлять параметр gotopic в хэш или нет
		 */
		prev: function ( useHash ) {
			return this._go( 'prev' ).apply(this, arguments);
		}
	});
})(jQuery);