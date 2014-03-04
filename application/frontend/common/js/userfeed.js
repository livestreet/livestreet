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

		// Подписаться / отписаться
		$('.js-userfeed-subscribe').on('click', function () {
			var oCheckbox = $(this);

			_this[ oCheckbox.is(':checked') ? 'subscribe' : 'unsubscribe' ]('blogs', oCheckbox.data('id'));
		});

		// Подгрузка контента
		$('.js-more-userfeed').more({
			url: aRouter['feed'] + 'get_more',
			target: '#userfeed-topic-list'
		});
	};

	/**
	 * Подписаться / отписаться
	 */
	this.subscribeAccessor = function(sName) {
		return function (sType, iId) {
			var sUrl = aRouter['feed'] + sName + '/',
				oParams = { 'type': sType, 'id': iId };

			ls.ajax.load(sUrl, oParams, function(oResponse) {
				if ( ! oResponse.bStateError ) {
					ls.msg.notice(oResponse.sMsgTitle, oResponse.sMsg);
					ls.hook.run('ls_userfeed_subscribe_after', [sType, iId, oResponse]);
				}
			});
		}
	};

	/**
	 * Подписаться
	 *
	 * @param  {String} sType Тип
	 * @param  {Number} iId   ID объекта
	 */
	this.subscribe = function(sType, iId) {
		this.subscribeAccessor('subscribe').apply(this, arguments);
	};

	/**
	 * Отписаться
	 *
	 * @param  {String} sType Тип
	 * @param  {Number} iId   ID объекта
	 */
	this.unsubscribe = function(sType, iId) {
		this.subscribeAccessor('unsubscribe').apply(this, arguments);
	};

	return this;
}).call(ls.userfeed || {},jQuery);