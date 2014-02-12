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
	 * Дефолтные опции
	 */
	var defaults = {

	};

	/**
	 * Инициализация
	 *
	 * @param  {Object} options Опции
	 */
	this.init = function(options) {
		this.options = $.extend({}, defaults, options);
	};

	/**
	 * Удаление списка писем
	 */
	this.removeTalks = function() {
		if ($('.form_talks_checkbox:checked').length == 0) {
			return false;
		}
		$('#form_talks_list_submit_del').val(1);
		$('#form_talks_list_submit_read').val(0);
		$('#form_talks_list').submit();
		return false;
	};

	/**
	 * Пометка о прочтении писем
	 */
	this.makeReadTalks = function() {
		if ($('.form_talks_checkbox:checked').length == 0) {
			return false;
		}
		$('#form_talks_list_submit_read').val(1);
		$('#form_talks_list_submit_del').val(0);
		$('#form_talks_list').submit();
		return false;
	};

	return this;
}).call(ls.talk || {},jQuery);
