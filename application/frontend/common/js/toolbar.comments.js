/**
 * Кнопка подгрузки и навигации по новым комментариям
 *
 * @module ls/toolbar/comments
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */


(function($) {
	"use strict";

	$.widget( "livestreet.lsToolbarComments", {
		/**
		 * Дефолтные опции
		 */
		options: {
			// Блок с комментариями
			target: '.js-comments',

			// Селекторы
			selectors: {
				// Кнопка обновления
				update: '.js-toolbar-comments-update',

				// Счетчик новых комментариев
				counter: '.js-toolbar-comments-count'
			}
		},

		/**
		 * Конструктор
		 *
		 * @constructor
		 * @private
		 */
		_create: function () {
			this.elements = {
				update:   this.element.find(this.options.selectors.update),
				counter:  this.element.find(this.options.selectors.counter),
				comments: typeof target === 'object' ? target : $(this.options.target)
			};

			// Обновляем счетчик новых комментариев
			this.updateCounter();

			//
			// События
			//

			// Обновление
			this._on( this.elements.update, { 'click': this.update } );

			// Прокрутка к следующему новому комментарию
			this._on( this.elements.counter, { 'click': this.scroll } );
		},

		/**
		 * Обновление счетчика
		 *
		 * @param {Number} count (optional) Кол-во новых комментариев
		 */
		updateCounter: function(count) {
			count = typeof count === 'undefined' ? this.elements.comments.lsComments('getCommentsNew').length : count;
			count ? this.elements.counter.show().text( count ) : this.elements.counter.hide();
		},

		/**
		 * Обновление
		 */
		update: function() {
			this.elements.update.addClass( ls.options.classes.states.active );
			this.elements.comments.lsComments('load', null, true, {
				done: function () {
					this.updateCounter();
				}.bind(this),
				success: function () {
					this.elements.update.removeClass( ls.options.classes.states.active );
				}.bind(this)
			});
		},

		/**
		 * Прокрутка к следующему новому комментарию
		 */
		scroll: function() {
			var commentsNew = this.elements.comments.lsComments('getCommentsNew'),
				comment = commentsNew.eq(0);

			if ( ! commentsNew.length ) return;

			// Если новый комментарий находится в свернутой ветке разворачиваем все ветки
			if ( ! comment.is(':visible') ) this.elements.comments.lsComments('unfoldAll');

			// Обновляем счетчик новых комментариев
			this.updateCounter( commentsNew.length - 1 );

			comment.removeClass( this.elements.comments.lsComments('option', 'classes.states.new') );
			this.elements.comments.lsComments('scrollToComment', comment);
		}
	});
})(jQuery);