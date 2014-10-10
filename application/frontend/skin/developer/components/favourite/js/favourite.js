/**
 * Избранное
 *
 * @module ls/favourite
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */


(function($) {
	"use strict";

	$.widget( "livestreet.lsFavourite", {
		/**
		 * Дефолтные опции
		 */
		options: {
			// Ссылки
			urls: {
				// Добавить/удалить из избранного
				toggle: null,
			},
			// Селекторы
			selectors: {
				// Кнопка добавить/удалить из избранного
				toggle: '.js-favourite-toggle',
				// Счетчик
				count: '.js-favourite-count'
			},
			// Классы
			classes: {
				// Добавлено в избранное
				added: 'favourite--added',
				// Кол-во добавивших в избранное больше нуля
				has_counter: 'favourite--has-counter',
			},
			// Параметры отправляемые при каждом аякс запросе
			params: {}
		},

		/**
		 * Конструктор
		 *
		 * @constructor
		 * @private
		 */
		_create: function () {
			this.options.params = $.extend({}, this.options.params, ls.utils.getDataOptions(this.element, 'param'));

			this.elements = {
				toggle: this.element.find( this.options.selectors.toggle ),
				count: this.element.find( this.options.selectors.count )
			};

			// Обработка кликов по кнопкам голосования
			this._on({ 'click': this.toggle });
		},

		/**
		 * Добавить/удалить из избранного
		 */
		toggle: function() {
			this.options.params.type = ! this.element.hasClass(this.options.classes.added);

			ls.ajax.load(this.options.urls.toggle, this.options.params, function(oResponse) {
				if (oResponse.bStateError) {
					ls.msg.error(null, oResponse.sMsg);
				} else {
					ls.msg.notice(null, oResponse.sMsg);

					this.element.removeClass(this.options.classes.added);

					if (oResponse.bState) {
						this.element.addClass(this.options.classes.added).attr('title', ls.lang.get('favourite.remove'));
					} else {
						this.element.attr('title', ls.lang.get('favourite.add'));
					}

					if (this.elements.count) {
						if (oResponse.iCount > 0) {
							this.element.addClass(this.options.classes.has_counter);
							this.elements.count.show().text(oResponse.iCount);
						} else {
							this.element.removeClass(this.options.classes.has_counter);
						}
					}

					this._trigger('aftertogglesuccess', null, { context: this, response: oResponse });
				}
			}.bind(this));
		}
	});
})(jQuery);