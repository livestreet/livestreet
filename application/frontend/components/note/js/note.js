/**
 * Заметки
 *
 * @module ls/usernote
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
	"use strict";

	$.widget( "livestreet.lsNote", $.livestreet.lsComponent, {
		/**
		 * Дефолтные опции
		 */
		options: {
			// Ссылки
			urls: {
				save:   null,
				remove: null
			},

			// Селекторы
			selectors: {
				body:           '.js-user-note-body',
				text:           '.js-user-note-text',
				add:            '.js-user-note-add',
				actions:        '.js-user-note-actions',
				actions_edit:   '.js-user-note-actions-edit',
				actions_remove: '.js-user-note-actions-remove',

				form:        '.js-user-note-form',
				form_text:   '.js-user-note-form-text',
				form_cancel: '.js-user-note-form-cancel'
			},

			params: {}
		},

		/**
		 * Конструктор
		 *
		 * @constructor
		 * @private
		 */
		_create: function () {
			this._super();

			// Добавление
			this._on( this.elements.add, { click: 'onShowFormClick' } );

			// Редактирование
			this._on( this.elements.actions_edit, { click: 'onShowFormClick' } );

			// Отмена редактирования
			this._on( this.elements.form_cancel, { click: 'hideForm' } );

			// Удаление
			this.elements.actions_remove.on('click' + this.eventNamespace, function (e) {
				this.remove();
				e.preventDefault();
			}.bind( this ));

			// Сохранение
			this.elements.form.on('submit' + this.eventNamespace, function (e) {
				this.save();
				e.preventDefault();
			}.bind( this ));
		},

		/**
		 * Добавление/Редактирование
		 */
		onShowFormClick: function( event ) {
			event.preventDefault();
			this.showForm();
		},

		/**
		 * Показывает форму редактирования
		 */
		showForm: function( event ) {
			this.elements.body.hide();
			this.elements.form.show();
			this.elements.form_text.val( $.trim(this.elements.text.html()) ).select();
		},

		/**
		 * Скрывает форму редактирования
		 */
		hideForm: function() {
			this.elements.body.show();
			this.elements.form.hide();
		},

		/**
		 * Сохраняет заметку
		 */
		save: function() {
			this._setParam( 'text', this.elements.form_text.val() );

			this._submit( 'save', this.elements.form, function ( response ) {
				this.elements.text.html(response.sText).show();
				this.elements.add.hide();
				this.elements.actions.show();
				this.hideForm();
			});
		},

		/**
		 * Удаление заметки
		 */
		remove: function() {
			this._load( 'remove', function () {
				this.elements.text.empty().hide();
				this.elements.add.show();
				this.elements.actions.hide();
			});
		}
	});
})(jQuery);