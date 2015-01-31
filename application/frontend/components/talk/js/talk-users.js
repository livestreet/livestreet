/**
 * Добавление / удаление пользователей из личных сообщений
 *
 * @module ls/talk/users
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
	"use strict";

	$.widget( "livestreet.lsTalkUsers", $.livestreet.lsUserListAdd, {
		/**
		 * Дефолтные опции
		 */
		options: {
			urls: {
				add: aRouter['talk'] + 'ajaxaddtalkuser/',
				inactivate: aRouter['talk'] + 'ajaxdeletetalkuser/'
			},
			selectors: {
				// Кнопка отключения пользователя от диалога
				item_inactivate: '.js-message-users-user-inactivate',
				// Кнопка повторного приглашения пользователя в диалог
				item_activate: '.js-message-users-user-activate'
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

			this._super();

			// Отключение пользователя от диалога
			this.elements.list.on('click' + this.eventNamespace, this.options.selectors.item_inactivate, function (e) {
				_this.inactivate( $(this) );
				e.preventDefault();
			});

			// Повторное приглашение пользователя в диалог
			this.elements.list.on('click' + this.eventNamespace, this.options.selectors.item_activate, function (e) {
				_this.add( [ $(this).data('user-login') ] );
				e.preventDefault();
			});
		},

		/**
		 * Активирует пользователя при его повторном добавлении
		 */
		_onUserAdd: function ( user ) {
			this.userActivate( user.user_id );
		},

		/**
		 * Повторное приглашение пользователя в диалог
		 */
		inactivate: function ( button ) {
			var userId = button.data( 'user-id' );

			this._load( 'inactivate', { user_id: userId }, function( response ) {
				this.userInactivate( userId );

				this._trigger( "afterinactivate", null, { context: this, response: response } );
			});
		},

		/**
		 * Активирует пользователя при его повторном добавлении
		 */
		userActivate: function ( userId ) {
			this._getUserById( userId ).removeClass( 'inactive' );
		},

		/**
		 * Отключения пользователя от диалога
		 */
		userInactivate: function ( userId ) {
			this._getUserById( userId ).addClass( 'inactive' );
		},
	});
})(jQuery);