/**
 * Добавление / удаление пользователей из личных сообщений
 *
 * @module message_users
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
	"use strict";

	$.widget( "livestreet.message_users", $.livestreet.user_list_add, {
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
		_onUserAdd: function (oUser) {
			this.userActivate(oUser.iUserId);
		},

		/**
		 * Повторное приглашение пользователя в диалог
		 */
		inactivate: function (oButton) {
			var iUserId = oButton.data('user-id'),
				oParams = {
					iUserId: iUserId
				};

			oParams = $.extend({}, oParams, this.options.params);

			ls.ajax.load(this.options.urls.inactivate, oParams, function(oResponse) {
				ls.msg.notice(null, oResponse.sMsg);

				this.userInactivate(iUserId);

				this._trigger("afterinactivate", null, { context: this, response: oResponse, oParams: oParams });
			}.bind(this));
		},

		/**
		 * Активирует пользователя при его повторном добавлении
		 */
		userActivate: function (iUserId) {
			this._getUserById( iUserId ).removeClass('inactive');
		},

		/**
		 * Отключения пользователя от диалога
		 */
		userInactivate: function (iUserId) {
			this._getUserById( iUserId ).addClass('inactive');
		},
	});
})(jQuery);