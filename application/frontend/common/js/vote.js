/**
 * Голосование
 *
 * @module ls/vote
 * @dependencies jquery, jquery.widget, tooltip, ls.utils, ls.ajax
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
	"use strict";

	$.widget( "livestreet.vote", {
		/**
		 * Дефолтные опции
		 */
		options: {
			// Ссылки
			urls: {
				// Голосование
				vote: null,
				// Информация о голосовании
				info: null
			},
			// Селекторы
			selectors: {
				// Кнопки голосования
				item:   '.js-vote-item',
				// Рейтинг
				rating: '.js-vote-rating',
			},
			// Классы
			classes : {
				// Пользователь проголосовал
				voted:          'vote--voted',
				// Не проголосовал
				not_voted:      'vote--not-voted',
				// Понравилось
				voted_up:       'vote--voted-up',
				// Не понравилось
				voted_down:     'vote--voted-down',
				// Воздержался
				voted_zero:     'vote--voted-zero',

				// Рейтинг больше нуля
				count_positive: 'vote--count-positive',
				// Меньше нуля
				count_negative: 'vote--count-negative',
				// Равен нулю
				count_zero:     'vote--count-zero',

				// Рейтинг скрыт
				rating_hidden:  'vote--rating-hidden',
			},
			// Параметры отправляемые при каждом аякс запросе
			params: {},
			// Опции тултипа с информацией о голосовании
			tooltip_options: {}
		},

		/**
		 * Конструктор
		 *
		 * @constructor
		 * @private
		 */
		_create: function () {
			var _this = this;

			this.options.params = $.extend({}, this.options.params, ls.utils.getDataOptions(this.element, 'param'));

			this.elements = {};
			this.elements.rating = this.element.find(this.options.selectors.rating);
			this.elements.items = this.element.find(this.options.selectors.item);

			// Обработка кликов по кнопкам голосования
			if ( ! this.element.hasClass(this.options.classes.voted) ) {
				this._on( this.elements.items, {
					'click': function (e) {
						_this.vote( $(e.currentTarget).data('vote-value') );
						e.preventDefault();
					}
				});
			}

			// Иниц-ия тултипа с информацией о голосовании
			// Показываем инфо-ию только если рейтинг отображается
			if ( ! this.element.hasClass(this.options.classes.rating_hidden) ) {
				this.info();
			}
		},

		/**
		 * Голосование
		 *
		 * @param  {Number} iValue    Значение
		 */
		vote: function(iValue) {
			var oParams = $.extend({}, { value: iValue }, this.options.params);

			ls.ajax.load(this.options.urls.vote, oParams, function (oResponse) {
				if (oResponse.bStateError) {
					ls.msg.error(null, oResponse.sMsg);
				} else {
					ls.msg.notice(null, oResponse.sMsg);

					oResponse.iRating = parseFloat(oResponse.iRating);

					this.element
						.removeClass(this.options.classes.count_negative + ' ' + this.options.classes.count_positive + ' ' + this.options.classes.count_zero)
						.removeClass(this.options.classes.rating_hidden + ' ' + this.options.classes.not_voted)
						.addClass(this.options.classes.voted)
						.addClass(this.options.classes[ iValue > 0 ? 'voted_up' : ( iValue < 0 ? 'voted_down' : 'voted_zero' ) ])
						.addClass(this.options.classes[ oResponse.iRating > 0 ? 'count_positive' : ( oResponse.iRating < 0 ? 'count_negative' : 'count_zero' ) ]);

					this.elements.rating.text(oResponse.iRating);

					// Не обрабатываем клики после голосования
					this._off(this.elements.items, 'click');

					// Удаляем подсказки
					this.elements.items.removeAttr('title');

					// Иниц-ия тултипа
					this.info().tooltip('show');
				}
			}.bind(this));
		},

		/**
		 * Иниц-ия тултипа с информацией о голосовании
		 *
		 * @return {jQuery}
		 */
		info: function () {
			if ( ! this.options.urls.info ) return $();

			var oTooltipOptions = $.extend({}, {
				ajax: {
					url: this.options.urls.info,
					params: this.options.params
				}
			}, this.options.tooltip_options);

			return this.element.tooltip({
				ajax: {
					url: this.options.urls.info,
					params: this.options.params
				}
			});
		}
	});
})(jQuery);