/**
 * Лента
 * 
 * @module ls/userfeed
 * 
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

ls.userfeed = (function ($) {
	"use strict";

	this.isBusy = false;
	
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
	
	this.subscribe = function (sType, iId) {
		var url = aRouter['feed']+'subscribe/';
		var params = {'type':sType, 'id':iId};
		
		ls.hook.marker('subscribeBefore');
		ls.ajax.load(url, params, function(data) { 
			if (!data.bStateError) {
				ls.msg.notice(data.sMsgTitle,data.sMsg);
				ls.hook.run('ls_userfeed_subscribe_after',[sType, iId, data]);
			}
		});
	}
	
	this.unsubscribe = function (sType, iId) {
		var url = aRouter['feed']+'unsubscribe/';
		var params = {'type':sType, 'id':iId};
		
		ls.hook.marker('unsubscribeBefore');
		ls.ajax.load(url, params, function(data) { 
			if (!data.bStateError) {
				ls.msg.notice(data.sMsgTitle,data.sMsg);
				ls.hook.run('ls_userfeed_unsubscribe_after',[sType, iId, data]);
			}
		});
	}
	
	this.getMore = function () {
		if (this.isBusy) {
			return;
		}
		var lastId = $('#userfeed_last_id').val();
		if (!lastId) return;
		$('#userfeed_get_more').addClass('loading');
		this.isBusy = true;
		
		var url = aRouter['feed']+'get_more/';
		var params = {'last_id':lastId};
		
		ls.hook.marker('getMoreBefore');
		ls.ajax.load(url, params, function(data) {
			if (!data.bStateError && data.topics_count) {
				$('#userfeed_loaded_topics').append(data.result);
				$('#userfeed_last_id').attr('value', data.iUserfeedLastId);
			}
			if (!data.topics_count) {
				$('#userfeed_get_more').hide();
			}
			$('#userfeed_get_more').removeClass('loading');
			ls.hook.run('ls_userfeed_get_more_after',[lastId, data]);
			this.isBusy = false;
		}.bind(this));
	}
	
	return this;
}).call(ls.userfeed || {},jQuery);