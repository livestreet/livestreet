/**
 * Активность
 * 
 * @module ls/stream
 * 
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

ls.stream = (function ($) {
	"use strict";

	this.isBusy = false;
	this.sDateLast = null;

	/**
	 * Дефолтные опции
	 */
	var defaults = {
		selectors: {
			getMoreButton:  '#activity-get-more',
		}
	};

	/**
	 * Инициализация
	 *
	 * @param  {Object} options Опции
	 */
	this.init = function(options) {
		this.options = $.extend({}, defaults, options);
		
		var self = this;

		$(this.options.selectors.getMoreButton).on('click', function () {
			self.getMore(this);
		});
	};

	this.switchEventType = function (iType) {
		var url = aRouter['stream']+'switchEventType/';
		var params = {'type':iType};

		ls.hook.marker('switchEventTypeBefore');
		ls.ajax.load(url, params, function(data) {
			if (!data.bStateError) {
				ls.msg.notice(data.sMsgTitle,data.sMsg);
				ls.hook.run('ls_stream_switch_event_type_after',[params,data]);
			}
		});
	};

	/**
	 * Подгрузка событий
	 * @param  {Object} oGetMoreButton Кнопка
	 */
	this.getMore = function (oGetMoreButton) {
		if (this.isBusy) return;

		var $oGetMoreButton = $(oGetMoreButton),
			$oLastId = $('#activity-last-id');
			iLastId = $oLastId.val();

		if ( ! iLastId ) return;

		$oGetMoreButton.addClass('loading');
		this.isBusy = true;

		var params = $.extend({}, {
			'iLastId':   iLastId,
			'sDateLast': this.sDateLast
		}, ls.utilities.getDataOptions($oGetMoreButton, 'param'));

		var url = aRouter['stream'] + 'get_more' + (params.type ? '_' + params.type : '') + '/';

		ls.hook.marker('getMoreBefore');

		ls.ajax.load(url, params, function(data) {
			if ( ! data.bStateError && data.events_count ) {
				$('#activity-event-list').append(data.result);
				$oLastId.attr('value', data.iStreamLastId);
			}

			if ( ! data.events_count) {
				$oGetMoreButton.hide();
			}

			$oGetMoreButton.removeClass('loading');

			ls.hook.run('ls_stream_get_more_after',[iLastId, data]);

			this.isBusy = false;
		}.bind(this));
	};

	return this;
}).call(ls.stream || {},jQuery);