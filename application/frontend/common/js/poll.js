/**
 * Опрос
 *
 * @module ls/poll
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
	"use strict";

	$.widget( "livestreet.lsPoll", {
		/**
		 * Дефолтные опции
		 */
		options: {
			// Ссылки
			urls: {
				// Голосование за вариант
				vote: aRouter.ajax + 'poll/vote/'
			},

			// Селекторы
			selectors: {
				// Форма голосования
				form: '.js-poll-vote-form',

				// Кнопка проголосовать
				vote: '.js-poll-vote',

				// Кнопка воздержаться от голосования
				abstain: '.js-poll-abstain',

				// Результата опроса
				result: '.js-poll-result',

				// Вариант
				item: '.js-poll-result-item',

				// Кнопка сортировки вариантов
				sort: '.js-poll-result-sort'
			}
		},

		/**
		 * Конструктор
		 *
		 * @constructor
		 * @private
		 */
		_create: function () {
			var _this = this;

			this.elements = {
				form:    this.element.find( this.options.selectors.form ),
				vote:    this.element.find( this.options.selectors.vote ),
				abstain: this.element.find( this.options.selectors.abstain )
			};

			! this.elements.form.length && this.initResult();

			//
			// События
			//

			this._on( this.elements.vote, { 'click': this.vote.bind( this, false ) } );
			this._on( this.elements.abstain, { 'click': this.vote.bind( this, true ) } );
			this.element.on( 'click' + this.eventNamespace, this.option( 'selectors.sort' ), this.sort.bind(this) );
		},

		/**
		 * Иниц-ия результата
		 */
		initResult: function() {
			this.elements.sort = this.element.find( this.options.selectors.sort );
			this.elements.items = this.element.find( this.options.selectors.item );
			this.elements.result = this.element.find( this.options.selectors.result );
		},

		/**
		 * Голосование
		 */
		vote: function( abstain ) {
			ls.ajax.submit( this.option( 'urls.vote' ), this.elements.form, function( response ) {
				this.element.html( $.trim( response.sText ) );
				this.initResult();

				this._off( this.elements.vote, 'click' );
				this._off( this.elements.abstain, 'click' );
			}.bind(this), {
				submitButton: this.elements.vote,
				params:       { abstain: abstain ? 1 : 0 }
			});
		},

		/**
		 * Сортировка результата
		 */
		sort: function() {
			var type = this.elements.sort.hasClass( ls.options.classes.states.active ) ? 'position' : 'count';

			this.elements.items.sort( function (a, b) {
				return $(b).data(type) - $(a).data(type);
			});

			this.elements.sort.toggleClass( ls.options.classes.states.active );
			this.elements.result.html( this.elements.items );
		}
	});
})(jQuery);