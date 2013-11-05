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
		// Classes
		classes: {
			active: 'active'
		},

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
				url:         aRouter['ajax'] + 'favourite/topic/'
			},
			talk: {
				url:         aRouter['ajax'] + 'favourite/talk/'
			},
			comment: {
				url:         aRouter['ajax'] + 'favourite/comment/'
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
			type: ! data.toggle.hasClass(this.options.classes.active),
			id: data.targetId
		};
		
		ls.hook.marker('toggleBefore');

		ls.ajax.load(this.options.type[data.type].url, params, function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				ls.msg.notice(null, result.sMsg);

				data.toggle.removeClass(this.options.classes.active);

				if (result.bState) {
					data.toggle.addClass(this.options.classes.active).attr('title', ls.lang.get('talk_favourite_del'));
					this.showTags(data.type,data.targetId);
				} else {
					data.toggle.attr('title', ls.lang.get('talk_favourite_add'));
					this.hideTags(data.type,data.targetId);
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


	/**
	 * ID объекта
	 * 
	 * @private
	 */
	var _targetId = 0;

	/**
	 * Тип объекта
	 * 
	 * @private
	 */
	var _targetType = null;

	/**
	 * Показывает форму редактирования тегов
	 * 
	 * @param  {String} targetType
	 * @param  {String} targetId
	 */
	this.showEditTags = function(targetId, type, obj) {
		_targetType = type;
		_targetId = targetId;

		var form = $('#favourite-form-tags'),
			text = '',
			tags = $('.js-favourite-tags-' + _targetType + '-' + _targetId);

		tags.find('.js-favourite-tag-user-link').each(function(k, tag){
			if (text) {
				text = text + ', ' + $(tag).text();
			} else {
				text = $(tag).text();
			}
		});

		form.find('.js-form-favourite-tags-list').val(text);
		form.modal('show');

		return false;
	};

	/**
	 * Скрывает форму редактирования тегов
	 * 
	 * @param  {String} targetType
	 * @param  {String} targetId
	 */
	this.hideEditTags = function() {
		$('#favourite-form-tags').modal('hide');
		return false;
	};

	/**
	 * Сохраняет персональные теги
	 * 
	 * @param  {String} targetType
	 * @param  {String} targetId
	 */
	this.saveTags = function(form) {
		var url = aRouter['ajax'] + 'favourite/save-tags/';
		var submitButton = $('.js-favourite-form-submit');

		ls.hook.marker('saveTagsBefore');

		ls.ajax.submit(url, $(form), function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				this.hideEditTags();
				var tags = $('.js-favourite-tags-' + _targetType + '-' + _targetId);
				tags.find('.js-favourite-tag-user').detach();
				var edit = tags.find('.js-favourite-tag-edit');
				$.each(result.aTags,function(k,v){
					edit.before('<li class="' + _targetType + '-tags-tag ' + _targetType + '-tags-tag-user js-favourite-tag-user">, <a rel="tag" href="'+v.url+'" class="topic-tags-tag-link js-topic-tags-tag-link">'+v.tag+'</a></li>');
				});

				ls.hook.run('ls_favourite_save_tags_after',[form,result],this);
			}
		}.bind(this), {
			submitButton: submitButton,
			params: {
				target_id: _targetId,
				target_type: _targetType
			}
		});
		return false;
	};

	/**
	 * Показывает персональные теги
	 * 
	 * @param  {String} targetType
	 * @param  {String} targetId
	 */
	this.showTags = function(targetType,targetId) {
		$('.js-favourite-tags-'+targetType+'-'+targetId).find('.js-favourite-tag-edit').show();
	};

	/**
	 * Скрывает персональные теги
	 * 
	 * @param  {String} targetType
	 * @param  {String} targetId
	 */
	this.hideTags = function(targetType,targetId) {
		var tags=$('.js-favourite-tags-'+targetType+'-'+targetId);
		tags.find('.js-favourite-tag-user').detach();
		tags.find('.js-favourite-tag-edit').hide();
		this.hideEditTags();
	};

	return this;
}).call(ls.favourite || {},jQuery);