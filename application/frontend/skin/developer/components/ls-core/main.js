/**
 * Основной модуль
 *
 * @module ls
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

/**
 * Дополнительные функции
 */
ls = (function ($) {
	/**
	 * Дефолтные опции
	 *
	 * @private
	 */
	var _defaults = {
		production: false,

		classes: {
			states: {
				active: 'active',
				loading: 'loading',
				open: 'open'
			}
		}
	};

	/**
	 * Инициализация
	 *
	 * @param {Object} options Опции
	 */
	this.init = function (options) {
		this.options = $.extend({}, _defaults, options);
	};

	return this;
}).call(ls || {}, jQuery);