/**
 * Пополняемый список пользователей
 * 
 * @module ls/user_list_add
 * 
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

ls.user_list_add = (function ($) {
	"use strict";

	/**
	 * Дефолтные опции
	 */
	var defaults = {
		// Селекторы
		selectors: {
			container:       '.js-user-list-add',
			user_list:       '.js-user-list-add-users',
			user_item:       '.js-user-list-small-item',
			user_list_empty: '.js-user-list-small-empty',
			form: {
				form:   '.js-user-list-add-form',
				users:  '.js-user-list-add-form-users',
				submit: '.js-user-list-add-form-submit'
			},
			actions: {
				remove: '.js-user-list-add-user-remove'
			}
		},

		// Типы списков
		type: {
			// Приглашение пользователей в блог
			blog_invite: {
				url: {
					add:    aRouter['blog'] + 'ajaxaddbloginvite/',
					remove: aRouter['blog'] + 'ajaxremovebloginvite/'
				}
			},
			// Добавление участников личного сообщения
			message: {
				url: {
					add:    aRouter['talk'] + 'ajaxaddtalkuser/',
					remove: aRouter['talk'] + 'ajaxdeletetalkuser/'
				}
			},
			// Черный список
			blacklist: {
				url: {
					add:    aRouter['talk'] + 'ajaxaddtoblacklist/',
					remove: aRouter['talk'] + 'ajaxdeletefromblacklist/'
				}
			},
			// Добавление пользователей в свою активность
			activity: {
				url: {
					add:    aRouter['stream'] + 'ajaxadduser/',
					remove: aRouter['stream'] + 'ajaxremoveuser/'
				}
			},
			// Добавление пользователей в свою ленту
			userfeed: {
				url: {
					add:    aRouter['feed'] + 'ajaxadduser/',
					remove: aRouter['feed'] + 'ajaxremoveuser/'
				}
			}
		}
	};

	/**
	 * Инициализация
	 *
	 * @param  {Object} options Опции
	 */
	this.init = function(options) {
		var _this = this;

		this.options = $.extend({}, defaults, options);

		this.elements = {
			form: $(this.options.selectors.form.form),
			actions: {
				remove: $(this.options.selectors.actions.remove)
			}
		}

		// Добавление
		this.elements.form.on('submit', function(e) {
			var oForm = $(this),
				oContainer   = oForm.closest(_this.options.selectors.container),
				oFormUsers   = oForm.find(_this.options.selectors.form.users),
				oButton      = oForm.find(_this.options.selectors.form.submit),
				oUserList    = oContainer.find(_this.options.selectors.user_list),
				sUserList    = oFormUsers.val(),
				oEmptyAlert  = oContainer.find(_this.options.selectors.user_list_empty);

			if ( ! sUserList ) return false;

			// Блокируем форму
			oButton.prop('disabled', true).addClass(ls.options.classes.states.loading);
			oFormUsers.prop('disabled', true);

			_this.add(oContainer.data('type'), oContainer.data('target-id'), sUserList, { 
				add_success: function (oResponse) {
					oFormUsers.val('');
				},
				add_user_success: function (oUser) {
					oUserList.show().prepend(oUser.sUserHtml);
					oEmptyAlert.hide();
				},
				add_after: function (oResponse) {
					// Разблокировываем форму
					oButton.prop('disabled', false).removeClass(ls.options.classes.states.loading);
					oFormUsers.prop('disabled', false).focus();
				}
			});

			e.preventDefault();
		});

		// Удаление
		$(document).on('click', this.options.selectors.actions.remove, function(e) {
			var oButton     = $(this),
				oContainer  = oButton.closest(_this.options.selectors.container),
				oUserList   = oContainer.find(_this.options.selectors.user_list),
				oEmptyAlert = oContainer.find(_this.options.selectors.user_list_empty);

			_this.remove(oContainer.data('type'), oContainer.data('target-id'), oButton.data('user-id'), function (oResponse, iUserId) {
				oContainer.find(this.options.selectors.user_item + '[data-user-id=' + iUserId + ']').fadeOut(300, function () {
					$(this).remove();

					// Скрываем список если пользователей в нем нет
					if ( ! oUserList.find(_this.options.selectors.user_item).length ) {
						oUserList.hide();
						oEmptyAlert.show();
					}
				});
			});

			e.preventDefault();
		});
	};

	/**
	 * Добавление пользователя
	 */
	this.add = function(sType, iTargetId, sUserList, aCallbacks) {
		if ( ! sUserList ) return false;

		var sUrl = this.options.type[sType].url.add,
			oParams = {
				iTargetId: iTargetId,
				sUserList: sUserList
			};

		ls.ajax.load(sUrl, oParams, function(oResponse) {
			if (oResponse.bStateError) {
				ls.msg.error(null, oResponse.sMsg);
			} else {
				$.each(oResponse.aUsers, function (iIndex, oUser) {
					if (oUser.bStateError) {
						ls.msg.error(null, oUser.sMsg);
					} else {
						ls.msg.notice(null, ls.lang.get('common.success.add'));

						if (typeof aCallbacks.add_user_success === 'function') aCallbacks.add_user_success.call(this, oUser);
					}		
				});
				
				if (typeof aCallbacks.add_success === 'function') aCallbacks.add_success.call(this, oResponse);
			}

			if (typeof aCallbacks.add_after === 'function') aCallbacks.add_after.call(this, oResponse);
		}.bind(this));
	};

	/**
	 * Удаление пользователя
	 */
	this.remove = function(sType, iTargetId, iUserId, fCallbackSuccess) {
		var sUrl = this.options.type[sType].url.remove,
			oParams = {
				iTargetId: iTargetId,
				iUserId: iUserId
			};

		ls.ajax.load(sUrl, oParams, function(oResponse) {
			if (oResponse.bStateError) {
				ls.msg.error(null, oResponse.sMsg);
			} else {
				ls.msg.notice(null, ls.lang.get('common.success.remove'));

				if (typeof fCallbackSuccess === 'function') fCallbackSuccess.call(this, oResponse, iUserId);
			}
		}.bind(this));
	};

	return this;
}).call(ls.user_list_add || {}, jQuery);