/**
 * Теги
 * 
 * @module ls/tags
 * 
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

ls.tags = (function ($) {
	"use strict";

	/**
	 * ID текущего объекта
	 * 
	 * @private
	 */
	var _targetId = 0;

	/**
	 * Тип текущего объекта
	 * 
	 * @private
	 */
	var _targetType = null;

	/**
	 * Дефолтные опции
	 * 
	 * @private
	 */
	var _defaults = {
		// Селекторы
		selectors: {
			form:             '#favourite-form-tags',
			submitForm:       '#js-favourite-form',
			submitButton:     '.js-tags-form-submit',
			submitInputList:  '.js-tags-form-input-list',
			tag:              '.js-tag-list-item-tag',
			tagPersonal:      '.js-tag-list-item-tag-personal',
			editPersonalTags: '.js-favourite-tag-edit'
		},
		// Роуты
		routers: {
			save: aRouter['ajax'] + 'favourite/save-tags/'
		}
	};

	/**
	 * Инициализация
	 *
	 * @param {Object} options Опции
	 */
	this.init = function(options) {
		var _this = this;

		this.options = $.extend({}, _defaults, options);

		this.oForm = $(this.options.selectors.form);
		this.oSubmitButton = $(this.options.selectors.submitButton);
		this.oSubmitForm = $(this.options.selectors.submitForm);
		this.oSubmitInputList = $(this.options.selectors.submitInputList);
		this.oEditPersonalTags = $(this.options.selectors.editPersonalTags);

		// Показываем форму редактирования персональных тегов
		this.oEditPersonalTags.on('click', function (e) {
			var oElement = $(this);

			_this.setTarget(oElement.data('id'), oElement.data('type'));
			_this.showForm();

			e.preventDefault();
		});

		// Сабмит формы редактирования персональных тегов
		this.oSubmitForm.on('submit', function (e) {
			this.save();
			e.preventDefault();
		}.bind(this));
	};

	/**
	 * Устанавливает id и тип текущего объекта
	 * 
	 * @param {Number} iId   ID объекта
	 * @param {String} sType Тип объекта
	 */
	this.setTarget = function(iId, sType) {
		_targetId = iId;
		_targetType = sType;
	};

	/**
	 * Сохраняет персональные теги
	 */
	this.save = function() {
		var _this = this;

		ls.hook.marker('saveTagsBefore');

		ls.ajax.submit(this.options.routers.save, this.oSubmitForm, function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				var tagsContainer = $('.js-tags-' + _targetType + '-' + _targetId),
					aTags = [];

				$.each(result.aTags, function(k, v) {
					aTags.push('<li class="tag-list-item tag-list-item-tag tag-list-item-tag-personal js-tag-list-item-tag-personal">, ' +
								'<a rel="tag" href="' + v.url + '" class="">' + v.tag + '</a></li>');
				});

				this.hideForm();

				tagsContainer.find(this.options.selectors.tagPersonal).remove();
				tagsContainer.find(this.options.selectors.editPersonalTags).before( aTags.join('') );

				ls.hook.run('ls_favourite_save_tags_after', [this.oSubmitForm, result], this);
			}
		}.bind(this), {
			submitButton: _this.oSubmitButton,
			params: {
				target_id: _targetId,
				target_type: _targetType
			}
		});

		return false;
	};

	/**
	 * Показывает форму редактирования тегов
	 */
	this.showForm = function() {
		var aTags = [];

		$('.js-tags-' + _targetType + '-' + _targetId).find(this.options.selectors.tagPersonal + ' a').each(function(k, tag) {
			aTags.push( $(tag).text() );
		});

		this.oSubmitInputList.val( aTags.join(', ') );
		this.oForm.lsModal('show');

		return false;
	};

	/**
	 * Скрывает форму редактирования тегов
	 */
	this.hideForm = function() {
		this.oForm.lsModal('hide');
	};

	/**
	 * Показывает персональные теги
	 * 
	 * @param {Number} iTargetId   ID объекта
	 * @param {String} sTargetType Тип объекта
	 */
	this.showPersonalTags = function(sTargetType, iTargetId) {
		$('.js-tags-' + sTargetType + '-' + iTargetId).find(this.options.selectors.editPersonalTags).show();
	};

	/**
	 * Скрывает персональные теги
	 * 
	 * @param {Number} iTargetId   ID объекта
	 * @param {String} sTargetType Тип объекта
	 */
	this.hidePersonalTags = function(sTargetType, iTargetId) {
		var tagsContainer = $('.js-tags-' + sTargetType + '-' + iTargetId);

		tagsContainer.find(this.options.selectors.tagPersonal).remove();
		tagsContainer.find(this.options.selectors.editPersonalTags).hide();
		this.hideForm();
	};

	return this;
}).call(ls.tags || {}, jQuery);