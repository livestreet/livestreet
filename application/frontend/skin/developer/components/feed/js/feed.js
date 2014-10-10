/**
 * Лента
 *
 * @module ls/feed
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
	"use strict";

	$.widget( "livestreet.lsFeed", {
		/**
		 * Дефолтные опции
		 */
		options: {
			// Ссылки
			urls: {
				// Подгрузка топиков
				more: null
			},

			// Селекторы
			selectors: {
				// Список топиков
				list:  '.js-feed-topic-list',

				// Кнопка подгрузки
				more:  '.js-feed-more'
			}
		},

		/**
		 * Конструктор
		 *
		 * @constructor
		 * @private
		 */
		_create: function () {
			var _this = this;

			this.elements = {
				list: this.element.find( this.option( 'selectors.list' ) ),
				more: this.element.find( this.option( 'selectors.more' ) )
			};

			// Подгрузка топиков
			this.elements.more.more({
				url: this.option( 'urls.more' ),
				target: this.elements.list,
			});
		},
	});
})(jQuery);