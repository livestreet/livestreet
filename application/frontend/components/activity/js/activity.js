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

	$.widget( "livestreet.lsActivity", $.livestreet.lsComponent, {
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
				list: '.js-activity-event-list',
				// Событие
				event: '.js-activity-event',
				// Кнопка подгрузки событий
				more: '.js-activity-more'
			}
		},

		/**
		 * Конструктор
		 *
		 * @constructor
		 * @private
		 */
		_create: function () {
			this._super();

			// Подгрузка событий
			this.elements.more.lsMore({
				urls: {
					load: this.option( 'urls.more' ),
				},
				target: this.elements.list,
				beforeload: function (e, context) {
					context._setParam( 'date_last', this.getDateLast() );
				}.bind( this )
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