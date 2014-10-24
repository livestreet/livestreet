/**
 * Таймер
 *
 * @module timer
 * 
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

ls.timer = (function ($) {
	"use strict";

	var _aTimers = {};

	/**
	 * Запуск метода через определенный период, поддерживает пролонгацию
	 */
	this.run = function(oContext, fMethod, sUniqKey, aParams, iTime) {
		iTime = iTime || 1500;
		aParams = aParams || [];
		sUniqKey = sUniqKey || Math.random();

		if (_aTimers[sUniqKey]) {
			clearTimeout(_aTimers[sUniqKey]);
			_aTimers[sUniqKey] = null;
		}

		var timeout = setTimeout(function(){
			clearTimeout(_aTimers[sUniqKey]);
			_aTimers[sUniqKey] = null;
			fMethod.apply(oContext, aParams);
		}.bind(this), iTime);

		_aTimers[sUniqKey] = timeout;
	};

	return this;
}).call(ls.timer || {},jQuery);