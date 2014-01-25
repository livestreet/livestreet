/**
 * Избранное
 * 
 * @module ls/favourite
 * 
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

ls.favourite = (function ($) {
	"use strict";

	/**
	 * Дефолтные опции
	 * 
	 * @private
	 */
	var _defaults = {
		// Selectors
		selectors: {
			// Блок добавления в избранное
			favourite: '.js-favourite',
			// Кнопка добавить/удалить из избранного
			toggle: '.js-favourite-toggle',
			// Счетчик
			count: '.js-favourite-count'
		},

		// Типы избранного
		type: {
			topic: {
				url: aRouter['ajax'] + 'favourite/topic/'
			},
			talk: {
				url: aRouter['ajax'] + 'favourite/talk/'
			},
			comment: {
				url: aRouter['ajax'] + 'favourite/comment/'
			}
		}
	};

	/**
	 * Инициализация
	 *
	 * @param {Object} options Опции
	 */
	this.init = function(options) {
		var self = this;

		this.options = $.extend({}, _defaults, options);

		$(this.options.selectors.favourite).each(function () {
			var element = $(this),
				data = {
					element:  element,
					type:     element.data('favourite-type'),
					targetId: element.data('favourite-id'),
					count:    element.find(self.options.selectors.count),
					toggle:   element.find(self.options.selectors.toggle)
				};

			element.on('click', function (e) {
				self.toggle(data);
				e.preventDefault();
			});
		});
	};

	/**
	 * Добавить\удалить из избранного
	 * 
	 * @param {Object} data
	 */
	this.toggle = function(data) {
		if ( ! this.options.type[data.type] ) return false;

		var params = {
			type: ! data.toggle.hasClass(ls.options.classes.states.active),
			id: data.targetId
		};
		
		ls.hook.marker('toggleBefore');

		ls.ajax.load(this.options.type[data.type].url, params, function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				ls.msg.notice(null, result.sMsg);

				data.toggle.removeClass(ls.options.classes.states.active);

				if (result.bState) {
					data.toggle.addClass(ls.options.classes.states.active).attr('title', ls.lang.get('favourite.remove'));
					ls.tags && ls.tags.showPersonalTags(data.type, data.targetId);
				} else {
					data.toggle.attr('title', ls.lang.get('favourite.add'));
					ls.tags && ls.tags.hidePersonalTags(data.type, data.targetId);
				}

				if (data.count) {
					if (result.iCount > 0) {
						data.count.show().text(result.iCount);
					} else {
						data.count.hide();
					}
				}

				ls.hook.run('ls_favourite_toggle_after',[data.targetId,data.toggle,data.type,params,result],this);
			}
		}.bind(this));
	};

	return this;
}).call(ls.favourite || {},jQuery);