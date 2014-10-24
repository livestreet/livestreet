/**
 * Костыли для IE
 *
 * @module ie
 * 
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

ls.ie = (function ($) {
	"use strict";

	/**
	 * Инициализация
	 */
	this.init = function() {
		if ($('html').hasClass('oldie')) {
			/**
			 * Эмуляция placeholder'ов в IE
			 */
			$('input[type=text], textarea').placeholder();
		}
	};

	return this;
}).call(ls.ie || {}, jQuery);