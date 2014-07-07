/**
 * Wall entry
 *
 * @module ls/wall/entry
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
	"use strict";

	$.widget( "livestreet.lsWallEntry", {
		/**
		 * Дефолтные опции
		 */
		options: {
			wall: null,

			// Ссылки
			urls: {
				remove: null
			},

			// Селекторы
			selectors: {
				wrapper: '.js-wall-entry-container',
				remove:  '.js-comment-remove',
				reply:   '.js-comment-reply',
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
				remove: this.element.find( this.option( 'selectors.remove' ) ),
				reply:  this.element.find( this.option( 'selectors.reply'  ) )
			};

			// ID поста
			this.id = this.element.data( 'id' );

			// Тип записи (комментарий/пост)
			this.type = this.element.data( 'type' );

			// Форма добавления комментария к текущему посту
			this.form = this.getType() === 'post' ? this.option( 'wall' ).lsWall( 'getFormById', this.id ) : null;

			//
			// События
			//

			// Удаление
			this._on( this.elements.remove, {
				click: function( event ) {
					this.remove();
					event.preventDefault();
				}
			});

			// Показать/скрыть форму ответа
			this._on( this.elements.reply, {
				click: function( event ) {
					this.formToggle();
					event.preventDefault();
				}
			});
		},

		/**
		 * Показать/скрыть форму ответа
		 */
		formToggle: function() {
			this.form.lsWallForm( 'toggle' );
		},

		/**
		 * Возвращает элементы записи
		 *
		 * @return {Array} Элементы записи
		 */
		getElements: function() {
			return this.elements;
		},

		/**
		 * Возвращает тип записи (комментарий/пост)
		 *
		 * @return {String} Тип записи (комментарий/пост)
		 */
		getType: function() {
			return this.type;
		},

		/**
		 * Удаление
		 */
		remove: function() {
			ls.ajax.load( this.option( 'urls.remove' ), { user_id: this.option( 'wall' ).lsWall( 'getUserId' ), id: this.id }, this.onRemove.bind( this ) );
		},

		/**
		 * Коллбэк вызываемый после удаления
		 */
		onRemove: function( response ) {
			this.element.fadeOut( 'slow', function() {
				this.element.remove();
				this.option( 'wall' ).lsWall( 'checkEmpty' );
			}.bind(this));

			this.option( 'wall' ).lsWall( 'getCommentWrapperById', this.id ).fadeOut( 'slow', function () {
				$( this ).remove();
			});
		},
	});
})(jQuery);