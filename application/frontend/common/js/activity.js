/**
 * Активность
 *
 * @module ls/activity
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

ls.activity = (function ($) {
	"use strict";

	/**
	 * Дефолтные опции
	 *
	 * @private
	 */
	var _defaults = {
	};

	/**
	 * Инициализация
	 *
	 * @param  {Object} options Опции
	 */
	this.init = function(options) {
		var _this = this;

		this.options = $.extend({}, _defaults, options);

		// Настройки
		$('.js-activity-settings-toggle').on('click', function () {
			_this.toggleEvent($(this).data('type'));
		});

		// Подгрузка контента
		$.each([ 'all', 'user', 'custom' ], function (iIndex, sValue) {
			$('.js-more-activity-' + sValue).more({
				url: aRouter['stream'] + 'get_more_' + sValue,
				target: '#activity-event-list',
				afterload: function (e, data) {
					data.context.element.data('param-s-date-last', data.response.sDateLast);
				}
			});
		});
	};

	/**
	 *
	 */
	this.toggleEvent = function (sType) {
		var sUrl = aRouter['stream'] + 'switchEventType/',
			oParams = {'type': sType};

		ls.hook.marker('switchEventTypeBefore');

		ls.ajax.load(sUrl, oParams, function(oResponse) {
			if ( ! oResponse.bStateError) {
				ls.msg.notice(oResponse.sMsgTitle, oResponse.sMsg);
				ls.hook.run('ls_stream_switch_event_type_after',[oParams, oResponse]);
			}
		});
	};

	return this;
}).call(ls.activity || {}, jQuery);