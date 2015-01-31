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

	$.widget( "livestreet.lsUserFollow", {
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
			}
		},

		/**
		 * Конструктор
		 *
		 * @constructor
		 * @private
		 */
		_create: function () {
			this._on({ click: this.onClick });
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
			ls.ajax.load( this.option( 'urls.follow' ), { aUserList: [ this.element.data('login') ] }, this.onFollow.bind(this) );
		},

		/**
		 * Коллбэк вызываемый при подписке
		 */
		onFollow: function( response ) {
			this.element.addClass( ls.options.classes.states.active ).text( ls.lang.get('user.actions.unfollow') );
		},

		/**
		 * Отписаться
		 */
		unfollow: function() {
			ls.ajax.load( this.option( 'urls.unfollow' ), { iUserId: this.element.data('id') }, this.onUnfollow.bind(this) );
		},

		/**
		 * Коллбэк вызываемый при отписке
		 */
		onUnfollow: function( response ) {
			this.element.removeClass( ls.options.classes.states.active ).text( ls.lang.get('user.actions.follow') );
		}
	});
})(jQuery);