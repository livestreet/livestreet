/**
 * Активность
 *
 * @module ls/activity
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
	"use strict";

	$.widget( "livestreet.lsActivity", {
		/**
		 * Дефолтные опции
		 */
		options: {
			// Ссылки
			urls: {
				// Подгрузка событий
				more: null
			},

			// Селекторы
			selectors: {
				// Список событий
				list:  '.js-activity-event-list',

				// Событие
				event: '.js-activity-event',

				// Кнопка подгрузки событий
				more:  '.js-activity-more'
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

			// Подгрузка событий
			this.elements.more.lsMore({
				url: this.option( 'urls.more' ),
				target: this.elements.list,
				beforeload: function (e, context) {
					context.options.params.date_last = _this.getDateLast();
				}
			});
		},

		/**
		 * Получает дату последнего подгруженного события
		 */
		getDateLast: function() {
			return this.elements.list.find( this.option( 'selectors.event' ) ).last().find( 'time' ).attr( 'datetime' );
		}
	});
})(jQuery);