/**
 * Хранения js данных
 *
 * @module notification
 * 
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

ls.msg = ls.notification = (function ($) {
	/**
	 * Опции
	 */
	this.options = {
		class_notice: 'n-notice',
		class_error: 'n-error'
	};

	/**
	 * Отображение информационного сообщения
	 */
	this.notice = function(title,msg){
		$.notifier.broadcast(title, msg, this.options.class_notice);
	};

	/**
	 * Отображение сообщения об ошибке
	 */
	this.error = function(title,msg){
		$.notifier.broadcast(title, msg, this.options.class_error);
	};

	return this;
}).call(ls.msg || {}, jQuery);