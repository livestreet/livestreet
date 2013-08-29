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
				url:         aRouter['ajax'] + 'favourite/topic/',
				targetName:  'idTopic'
			},
			talk: {
				url:         aRouter['ajax'] + 'favourite/talk/',
				targetName:  'idTalk'
			},
			comment: {
				url:         aRouter['ajax'] + 'favourite/comment/',
				targetName:  'idComment'
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
			var element = $(this);
			var data = {
				count: element.find(self.options.selectors.count),
				toggle: element.find(self.options.selectors.toggle)
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
		var $toggle = data.toggle,
			type = $toggle.data('favourite-type'),
			targetId = $toggle.data('favourite-id'),
			count = data.count;

		if ( ! this.options.type[type] ) return false;

		var params = {};
		params['type'] = ! $toggle.hasClass(this.options.classes.active);
		params[this.options.type[type].targetName] = targetId;
		
		ls.hook.marker('toggleBefore');

		ls.ajax(this.options.type[type].url, params, function(result) {
			$(this).trigger('toggle',[targetId,$toggle,type,params,result]);

			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				ls.msg.notice(null, result.sMsg);

				$toggle.removeClass(this.options.classes.active);

				if (result.bState) {
					$toggle.addClass(this.options.classes.active).attr('title', ls.lang.get('talk_favourite_del'));
					this.showTags(type,targetId);
				} else {
					$toggle.attr('title', ls.lang.get('talk_favourite_add'));
					this.hideTags(type,targetId);
				}

				if (count) {
					if (result.iCount > 0) {
						count.show().text(result.iCount);
					} else {
						count.hide();
					}
				}

				ls.hook.run('ls_favourite_toggle_after',[targetId,$toggle,type,params,result],this);
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

	this.hideEditTags = function() {
		$('#favourite-form-tags').modal('hide');
		return false;
	};

	this.saveTags = function(form) {
		var url = aRouter['ajax'] + 'favourite/save-tags/';
		var submitButton = $('.js-favourite-form-submit');

		ls.hook.marker('saveTagsBefore');

		ls.ajaxSubmit(url, $(form), function(result) {
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

	this.hideTags = function(targetType,targetId) {
		var tags=$('.js-favourite-tags-'+targetType+'-'+targetId);
		tags.find('.js-favourite-tag-user').detach();
		tags.find('.js-favourite-tag-edit').hide();
		this.hideEditTags();
	};

	this.showTags = function(targetType,targetId) {
		$('.js-favourite-tags-'+targetType+'-'+targetId).find('.js-favourite-tag-edit').show();
	};

	return this;
}).call(ls.favourite || {},jQuery);