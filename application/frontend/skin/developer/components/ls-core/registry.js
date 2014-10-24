/**
 * Хранения js данных
 *
 * @module i18n
 * 
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

ls.registry = (function ($) {
	"use strict";

	/**
	 * Данные
	 * 
	 * @private
	 */
	var _aData = {};

	/**
	 * Сохранение
	 */
	this.set = function(sName, data){
		if (typeof(sName)=='object') {
			$.each(sName,function(k,v) {
				_aData[k]=v;
			});
		} else {
			_aData[sName] = data;
		}
	};

	/**
	 * Получение
	 */
	this.get = function(sName){
		return _aData[sName];
	};

	return this;
}).call(ls.registry || {}, jQuery);