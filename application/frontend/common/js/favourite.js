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

			this.elements = {};
			this.elements.toggle = this.element.find(this.options.selectors.toggle);
			this.elements.count = this.element.find(this.options.selectors.count);

			// Обработка кликов по кнопкам голосования
			this._on({
				'click': function (e) {
					this.toggle();
					e.preventDefault();
				}
			});
		},

		/**
		 * Добавить/удалить из избранного
		 */
		toggle: function() {
			this.options.params.type = ! this.element.hasClass(ls.options.classes.states.active);

			ls.ajax.load(this.options.urls.toggle, this.options.params, function(oResponse) {
				if (oResponse.bStateError) {
					ls.msg.error(null, oResponse.sMsg);
				} else {
					ls.msg.notice(null, oResponse.sMsg);

					this.element.removeClass(ls.options.classes.states.active);

					if (oResponse.bState) {
						this.element.addClass(ls.options.classes.states.active).attr('title', ls.lang.get('favourite.remove'));
					} else {
						this.element.attr('title', ls.lang.get('favourite.add'));
					}

					if (this.elements.count) {
						if (oResponse.iCount > 0) {
							this.elements.count.show().text(oResponse.iCount);
						} else {
							this.elements.count.hide();
						}
					}

					this._trigger("aftertogglesuccess", null, { context: this, response: oResponse });
					//ls.hook.run('ls_favourite_toggle_after',[data.targetId,data.toggle,data.type,params,oResponse],this);
				}
			}.bind(this));
		}
	});
})(jQuery);