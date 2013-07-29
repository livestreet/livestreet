/**
 * Пагинация
 */

var ls = ls || {};

ls.pagination = (function($) {
	"use strict";

	/**
	 * Дефолтные опции
	 */
	var defaults = {
		keys: {
			additional: 'ctrl',
			next: 39,
			prev: 37
		},

		// Селекторы
		selectors: {
			pagination: '.js-pagination',
			next:       '.js-pagination-next',
			prev:       '.js-pagination-prev'
		}
	};

	this.oPagination = false;
	this.sLinkNext   = false;
	this.sLinkPrev   = false;

	/**
	 * Инициализация
	 *
	 * @param  {Object} options Опции
	 */
	this.init = function(options) {
		var self = this;

		this.options = $.extend({}, defaults, options);

		this.oPagination = $(this.options.selectors.pagination).eq(0);
		this.sLinkNext   = this.oPagination.find(this.options.selectors.next).attr('href');
		this.sLinkPrev   = this.oPagination.find(this.options.selectors.prev).attr('href');

		// Переход по страницам
		$(document).on('keyup', function (e) {
			var key = e.keyCode || e.which;

			if (self.options.keys.additional ? e[self.options.keys.additional + 'Key'] : true) {
				switch (key) {
					case self.options.keys.prev:
						self.prev();
						break;
					case self.options.keys.next:
						self.next();
						break;
				}
			}
		});
	};

	/**
	 * Переход на следующую страницу
	 *
	 * @param  {Boolean} bScroll Скролл к первому топику на странице
	 */
	this.next = function(bScroll) {
		if (this.sLinkNext) window.location = this.sLinkNext + (bScroll ? '#goTopic=0' : '');
	};

	/**
	 * Переход на предыдущую страницу
	 *
	 * @param  {Boolean} bScroll Скролл к последнему топику на странице
	 */
	this.prev = function(bScroll) {
		if (this.sLinkPrev) window.location = this.sLinkPrev + (bScroll ? '#goTopic=last' : '');
	};

	return this;
}).call(ls.pagination || {},jQuery);