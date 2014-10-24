/**
 * Модуль вспомогательных функций для разработчика
 *
 * @module ls/dev
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

ls.dev = (function ($) {
	/**
	 * Дефолтные опции
	 *
	 * @private
	 */
	var _defaults = { };

	/**
	 * Инициализация
	 *
	 * @param {Object} options Опции
	 */
	this.init = function (options) {
		this.options = $.extend({}, _defaults, options);
	};

	/**
	 * Дебаг сообщений
	 */
	this.debug = function() {
		if ( ls.options.production ) return;

		this.log.apply(this, arguments);
	};

	/**
	 * Лог сообщений
	 */
	this.log = function() {
		if ( window.console && window.console.log ) {
			Function.prototype.bind.call(console.log, console).apply(console, arguments);
		}
	};

	return this;
}).call(ls.dev || {}, jQuery);