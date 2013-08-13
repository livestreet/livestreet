/**
 * Заметки
 */

var ls = ls || {};

ls.usernote = (function($) {
	"use strict";

	/**
	 * Дефолтные опции
	 */
	var defaults = {
		// Роутеры
		routers: {
			save:   aRouter['profile'] + 'ajax-note-save/',
			remove: aRouter['profile'] + 'ajax-note-remove/'
		},

		// Селекторы
		selectors: {
			note:             '.js-user-note',
			noteContent:      '.js-user-note-content',
			noteText:         '.js-user-note-text',
			noteAddButton:    '.js-user-note-add-button',
			noteActions:      '.js-user-note-actions',
			noteEditButton:   '.js-user-note-edit-button',
			noteRemoveButton: '.js-user-note-remove-button',

			noteEdit:             '.js-user-note-edit',
			noteEditText:         '.js-user-note-edit-text',
			noteEditSaveButton:   '.js-user-note-edit-save',
			noteEditCancelButton: '.js-user-note-edit-cancel'
		}
	};

	/**
	 * Инициализация
	 *
	 * @param  {Object} options Опции
	 */
	this.init = function(options) {
		var self = this;

		this.options = $.extend({}, defaults, options);

		// Добавление
		$(this.options.selectors.note).each(function () {
			var oNote = $(this);

			var oVars = {
				oNote:         oNote,
				oNoteText:     oNote.find(self.options.selectors.noteText),
				oNoteEditText: oNote.find(self.options.selectors.noteEditText),
				oNoteContent:  oNote.find(self.options.selectors.noteContent),
				oNoteEdit:     oNote.find(self.options.selectors.noteEdit),
				oNoteAdd:      oNote.find(self.options.selectors.noteAddButton),
				oNoteActions:  oNote.find(self.options.selectors.noteActions),
				iUserId:       oNote.data('user-id')
			};

			// Показывает форму добавления
			oVars.oNote.find(self.options.selectors.noteAddButton).on('click', function (e) {
				self.showForm(oVars);
				e.preventDefault();
			}.bind(self));

			// Отмена
			oVars.oNote.find(self.options.selectors.noteEditCancelButton).on('click', function (e) {
				self.hideForm(oVars);
			});

			// Сохранение заметки
			oVars.oNote.find(self.options.selectors.noteEditSaveButton).on('click', function (e) {
				self.save(oVars);
			});

			// Удаление заметки
			oVars.oNote.find(self.options.selectors.noteRemoveButton).on('click', function (e) {
				self.remove(oVars);
				e.preventDefault();
			});

			// Редактирование заметки
			oVars.oNote.find(self.options.selectors.noteEditButton).on('click', function (e) {
				self.showForm(oVars);
				oVars.oNoteEditText.val( $.trim(oVars.oNoteText.html()) );
				e.preventDefault();
			});
		});
	};

	/**
	 * Показывает форму редактирования
	 * 
	 * @param  {Object} oVars Общие переменные
	 */
	this.showForm = function(oVars) {
		oVars.oNoteContent.hide();
		oVars.oNoteEdit.show();
		oVars.oNoteEditText.val( $.trim(oVars.oNoteText.html()) ).focus();
	};

	/**
	 * Скрывает форму редактирования
	 * 
	 * @param  {Object} oVars Общие переменные
	 */
	this.hideForm = function(oVars) {
		oVars.oNoteContent.show();
		oVars.oNoteEdit.hide();
	};

	/**
	 * Сохраняет заметку
	 * 
	 * @param  {Object} oVars Общие переменные
	 */
	this.save = function(oVars) {
		var params = {
			iUserId: oVars.iUserId, 
			text:    oVars.oNoteEditText.val()
		};

		ls.hook.marker('saveBefore');

		ls.ajax(this.options.routers.save, params, function (result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				oVars.oNoteText.html(result.sText).show();
				oVars.oNoteAdd.hide();
				oVars.oNoteActions.show();
				this.hideForm(oVars);

				ls.hook.run('ls_usernote_save_after',[params, result]);
			}
		}.bind(this));
	};

	/**
	 * Удаление заметки
	 * 
	 * @param  {Object} oVars Общие переменные
	 */
	this.remove = function(oVars) {
		var params = {
			iUserId: oVars.iUserId
		};

		ls.hook.marker('removeBefore');

		ls.ajax(this.options.routers.remove, params, function (result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				oVars.oNoteText.empty().hide();
				oVars.oNoteAdd.show();
				oVars.oNoteActions.hide();
				this.hideForm(oVars);

				ls.hook.run('ls_usernote_remove_after',[params, result]);
			}
		}.bind(this));
	};

	return this;
}).call(ls.usernote || {},jQuery);