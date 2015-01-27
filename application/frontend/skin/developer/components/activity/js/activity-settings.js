/**
 * Activity settings
 *
 * @module ls/activity/settings
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
	"use strict";

	$.widget( "livestreet.lsActivitySettings", $.livestreet.lsComponent, {
		/**
		 * Дефолтные опции
		 */
		options: {
			// Ссылки
			urls: {
				toggle_type: null
			},

			// Селекторы
			selectors: {
				type_checkbox: '.js-activity-settings-type-checkbox'
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

			this._on( this.elements.type_checkbox, { change: 'toggleEventType' } );
		},

		/**
		 * Сохранение настроек
		 */
		toggleEventType: function( event ) {
			this.option( 'params.type', $( event.target ).data( 'type' ) );

			this._load( 'toggle_type', function( response ) {} );
		}
	});
})(jQuery);