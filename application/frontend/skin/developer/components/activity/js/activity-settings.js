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

	$.widget( "livestreet.lsActivitySettings", {
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
			var _this = this;

			this.elements = {
				type_checkboxes: this.element.find( this.option( 'selectors.type_checkbox' ) )
			};

			this._on( this.elements.type_checkboxes, { change: this.toggleEventType } );
		},

		/**
		 * Сохранение настроек
		 */
		toggleEventType: function( event ) {
			var type = $( event.target ).data( 'type' );

			ls.ajax.load( this.option( 'urls.toggle_type' ), { 'type': type }, function( response ) {
				if ( ! response.bStateError ) {
					ls.msg.notice( response.sMsgTitle, response.sMsg );

					ls.hook.run( 'ls_activity_toggle_event_type_after', [ type, response ] );
				}
			});
		}
	});
})(jQuery);