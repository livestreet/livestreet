/**
 * Каптча
 * 
 * @module ls/captcha
 * 
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

ls.captcha = (function ($) {
	"use strict";

	/**
	 * jQuery объект каптчи
	 * 
	 * @private
	 */
	var _oCaptcha = null;

	/**
	 * Дефолтные опции
	 * 
	 * @private
	 */
	var _defaults = {
		// Селекторы
		selectors: {
			captcha: '.js-form-auth-captcha'
		}
	};

	/**
	 * Инициализация
	 *
	 * @param {Object} options Опции
	 */
	this.init = function(options) {
		this.options = $.extend({}, _defaults, options);

		_oCaptcha = $(this.options.selectors.captcha);

		// Обновляем каптчу при клике на нее
		_oCaptcha.on('click', function () {
			this.update();
		}.bind(this));
	};

	/**
	 * Получает url каптчи
	 */
	this.getUrl = function () {
		return PATH_FRAMEWORK_LIBS_VENDOR + '/kcaptcha/index.php?' + SESSION_NAME + '=' + SESSION_ID + '&n=' + Math.random();
	};

	/**
	 * Обновляет каптчу
	 */
	this.update = function () {
		_oCaptcha.css('background-image', 'url(' + this.getUrl() + ')');
	};

	return this;
}).call(ls.captcha || {}, jQuery);