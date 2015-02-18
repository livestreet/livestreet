/**
 * Wall form
 *
 * @module ls/wall/form
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
	"use strict";

	$.widget( "livestreet.lsWallForm", {
		/**
		 * Дефолтные опции
		 */
		options: {
			wall: null,

			// Ссылки
			urls: {
				add: null
			},

			// Селекторы
			selectors: {
				text:          '.js-wall-form-text',
				button_submit: '.js-wall-form-submit'
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

			// Элементы
			this.elements = {
				text:   this.element.find( this.option( 'selectors.text'   ) ),
				submit: this.element.find( this.option( 'selectors.submit' ) )
			};

			// ID поста
			this.id = this.element.data( 'id' );

			// Кнопка "Ответить" в посте
			this.reply = this.option( 'wall' ).lsWall( 'getEntryById', this.id ).lsWallEntry( 'getElement', 'reply' );

			// Отправка формы
			this._on({ submit: this.submit });
			this.elements.text.on( 'keydown' + this.eventNamespace, null, 'ctrl+return', this.submit.bind( this ) );

			// Разворачивание формы
			this._on( this.elements.text, { click: this.open } );

			// Сворачиваем открытые формы
			// при клике вне формы или кнопки Ответить
			this.document.on( 'mouseup' + this.eventNamespace, function( e ) {
				if ( e.which == 1 &&
					 this.isOpened() &&
					 ! this.element.is( e.target ) &&
					 ( ! this.reply || ( this.reply && ! this.reply.is( e.target ) ) ) &&
					 this.element.has( e.target ).length === 0 &&
					 ! this.elements.text.val() ) {

					// Сворачиваем форму если у поста формы есть комментарии или если форма корневая
					if ( this.option( 'wall' ).lsWall( 'getCommentsByPostId', this.id ).length || this.id === 0 ) {
						this.close();
					}
					// Если у поста нет комментариев то скрываем форму
					else {
						this.hide();
					}
				}
			}.bind( this ));
		},

		/**
		 * Отправка формы
		 */
		submit: function( event ) {
			var text = this.elements.text.val();

			ls.utils.formLock( this.element );
			this.option( 'wall' ).lsWall( 'add', this.id, text );

			event.preventDefault();
		},

		/**
		 * Разворачивает форму
		 */
		open: function() {
			this.element.addClass( ls.options.classes.states.open );
		},

		/**
		 * Сворачивает форму
		 */
		close: function() {
			this.element.removeClass( ls.options.classes.states.open );
			this.elements.text.val('');
		},

		/**
		 * Показать форму
		 */
		show: function() {
			this.element.show();
			this.open();
			this.elements.text.focus();
		},

		/**
		 * Скрыть форму
		 */
		hide: function() {
			this.element.hide();
		},

		/**
		 * Развернута форма или нет
		 */
		isOpened: function() {
			return this.element.hasClass( ls.options.classes.states.open );
		},

		/**
		 * Сворачивает/разворачивает форму
		 */
		expandToggle: function() {
			this[ this.isOpened() ? 'close' : 'open' ]();
		},

		/**
		 * Показывает/скрывает форму комментирования
		 */
		toggle: function() {
			this[ this.element.is( ':visible' ) ? 'hide' : 'show' ]();
		}
	});
})(jQuery);