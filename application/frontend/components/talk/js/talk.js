/**
 * Личные сообщения
 *
 * @module ls/talk
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

ls.talk = (function ($) {
	"use strict";

	/**
	 * Инициализация
	 *
	 * @param  {Object} options Опции
	 */
	this.init = function(options) {
		var _this = this;

		this.elements = {
			form: $('#talk-form'),
			form_action: $('#talk-form-action')
		}

		$('.js-talk-form-action:not([data-action=remove])').on('click', function (e) {
			_this.formAction( $(this).data('action') );
		});

		$('.js-talk-form-action[data-action=remove]').lsConfirm({
			message: ls.lang.get('common.remove_confirm'),
			onconfirm: function () {
				_this.formAction( 'remove' );
			}
		})

		// Выбор получателей в форме добавления
        $('.js-talk-add-user-choose').lsUserFieldChoose({
            urls: {
                modal: aRouter.ajax + 'modal-friend-list'
            }
        });
	};

	/**
	 * Установка экшена формы
	 */
	this.formSetAction = function(action) {
		if ( ! this.elements.form.find('input[type=checkbox]:checked').length ) return;

		this.elements.form_action.val(action);
		this.elements.form.submit();
	};

	return this;
}).call(ls.talk || {},jQuery);
