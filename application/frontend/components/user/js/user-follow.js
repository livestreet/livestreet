/**
 * Follow user
 *
 * @module ls/user/follow
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
	"use strict";

	$.widget( "livestreet.lsUserFollow", $.livestreet.lsComponent, {
		/**
		 * Дефолтные опции
		 */
		options: {
			// Ссылки
			urls: {
				// Подписаться
				follow: null,

				// Отписаться
				unfollow: null
			},
			classes: {
				active: 'active'
			},
			params: {}
		},

		/**
		 * Конструктор
		 *
		 * @constructor
		 * @private
		 */
		_create: function () {
			this._super();
			this._on({ click: 'onClick' });
		},

		/**
		 * Коллбэк вызываемый при клике на кнопку подписки
		 */
		onClick: function( event ) {
			this[ this.element.hasClass( ls.options.classes.states.active ) ? 'unfollow' : 'follow' ]();
			event.preventDefault();
		},

		/**
		 * Подписаться
		 */
		follow: function() {
			this._load( 'follow', { users: [ this.element.data('login') ] }, 'onFollow' );
		},

		/**
		 * Коллбэк вызываемый при подписке
		 */
		onFollow: function( response ) {
			this._addClass( 'active' ).text( ls.lang.get('user.actions.unfollow') );
		},

		/**
		 * Отписаться
		 */
		unfollow: function() {
			this._load( 'unfollow', { user_id: this.element.data('id') }, 'onUnfollow' );
		},

		/**
		 * Коллбэк вызываемый при отписке
		 */
		onUnfollow: function( response ) {
			this._removeClass( 'active' ).text( ls.lang.get('user.actions.follow') );
		}
	});
})(jQuery);