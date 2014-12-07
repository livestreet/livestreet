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

	$.widget( "livestreet.lsNote", {
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

			this.options = $.extend({}, this.options, ls.utils.getDataOptions(this.element, 'note'));

			// Получаем аякс параметры
			this.params = ls.utils.getDataOptions(this.element, 'param');

			// Получаем элементы
			this.elements = {};
			this.elements.container = this.element;

			this.elements.body           = this.elements.container.find(this.options.selectors.body);
			this.elements.text           = this.elements.body.find(this.options.selectors.text);
			this.elements.add            = this.elements.body.find(this.options.selectors.add);
			this.elements.actions        = this.elements.body.find(this.options.selectors.actions);
			this.elements.actions_edit   = this.elements.actions.find(this.options.selectors.actions_edit);
			this.elements.actions_remove = this.elements.actions.find(this.options.selectors.actions_remove);

			this.elements.form        = this.elements.container.find(this.options.selectors.form);
			this.elements.form_text   = this.elements.form.find(this.options.selectors.form_text);
			this.elements.form_cancel = this.elements.form.find(this.options.selectors.form_cancel);

			// Добавление
			this.elements.add.on('click', function (e) {
				_this.showForm();
				e.preventDefault();
			});

			// Редактирование
			this.elements.actions_edit.on('click', function (e) {
				_this.showForm();
				e.preventDefault();
			});

			// Отмена редактирования
			this.elements.form_cancel.on('click', this.hideForm.bind(this));

			// Удаление
			this.elements.actions_remove.on('click', function (e) {
				_this.remove();
				e.preventDefault();
			});

			// Сохранение
			this.elements.form.on('submit', function (e) {
				_this.save();
				e.preventDefault();
			});
		},

		/**
		 * Показывает форму редактирования
		 */
		showForm: function() {
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
			var oParams = {
				text: this.elements.form_text.val()
			};

			oParams = $.extend({}, oParams, this.params);

			ls.utils.formLock(this.elements.form);

			ls.ajax.load(this.options.urls.save, oParams, function (oResponse) {
				ls.utils.formUnlock(this.elements.form);

				if (oResponse.bStateError) {
					ls.msg.error(null, oResponse.sMsg);
				} else {
					this.elements.text.html(oResponse.sText).show();
					this.elements.add.hide();
					this.elements.actions.show();
					this.hideForm();
				}
			}.bind(this));
		},

		/**
		 * Удаление заметки
		 */
		remove: function() {
			ls.ajax.load(this.options.urls.remove, this.params, function (oResponse) {
				if (oResponse.bStateError) {
					ls.msg.error(null, oResponse.sMsg);
				} else {
					this.elements.text.empty().hide();
					this.elements.add.show();
					this.elements.actions.hide();
				}
			}.bind(this));
		}
	});
})(jQuery);