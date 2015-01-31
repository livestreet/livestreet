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

		$('.js-talk-form-action').on('click', function (e) {
			_this.formAction( $(this).data('action') );
		});
	};

	/**
	 * Установка экшена формы
	 */
	this.formAction = function(sName) {
		if ( ! this.elements.form.find('input[type=checkbox]:checked').length ) return;

		this.elements.form_action.val(sName);
		this.elements.form.submit();
	};

	return this;
}).call(ls.talk || {},jQuery);
