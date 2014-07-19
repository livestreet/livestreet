/**
 * Управление блогами в ленте
 *
 * @module ls/feed/blogs
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
	"use strict";

	$.widget( "livestreet.lsFeedBlogs", {
		/**
		 * Дефолтные опции
		 */
		options: {
			// Ссылки
			urls: {
				subscribe: null,
				unsubscribe: null
			},

			// Селекторы
			selectors: {
				checkbox: '.js-feed-blogs-subscribe'
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
				checkboxes: this.element.find( this.option( 'selectors.checkbox' ) )
			};

			this.type = 'blogs';

			this._on( this.elements.checkboxes, { change: this.toggleSubscribe } );
		},

		/**
		 * Сохранение настроек
		 */
		toggleSubscribe: function( event ) {
			var checkbox = $( event.target ),
				id       = checkbox.data( 'id' );

			ls.ajax.load(
				this.option( 'urls.' + ( checkbox.is(':checked') ? 'subscribe' : 'unsubscribe' ) ),
				{
					type: this.type,
					id: id
				},
				function( response ) {
					if ( ! response.bStateError ) {
						ls.msg.notice( response.sMsgTitle, response.sMsg );
					}
				}
			);
		}
	});
})(jQuery);