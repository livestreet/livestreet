/**
 * Избранное
 */

var ls = ls || {};

ls.favourite = (function ($) {
	"use strict";

	/**
	 * ID объекта
	 * @type {Number}
	 */
	var _targetId = 0;

	/**
	 * Тип объекта
	 * @type {Number}
	 */
	var _targetType = false;

	/**
	 * Дефолтные опции
	 */
	var _defaults = {
		active: 'active',
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
	 * @param  {Object} options Опции
	 */
	this.init = function(options) {
		var self = this;

		this.options = $.extend({}, _defaults, options);
	};

	/**
	* Переключение избранного
	*/
	this.toggle = function(idTarget, objFavourite, type) {
		if (!this.options.type[type]) { return false; }

		this.objFavourite = $(objFavourite);
		
		var params = {};
		params['type'] = !this.objFavourite.hasClass(this.options.active);
		params[this.options.type[type].targetName] = idTarget;
		
		ls.hook.marker('toggleBefore');
		ls.ajax(this.options.type[type].url, params, function(result) {
			$(this).trigger('toggle',[idTarget,objFavourite,type,params,result]);
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				var counter = $('#fav_count_' + type + '_'+idTarget);

				ls.msg.notice(null, result.sMsg);
				this.objFavourite.removeClass(this.options.active);

				if (result.bState) {
					this.objFavourite.addClass(this.options.active).attr('title', ls.lang.get('talk_favourite_del'));
					this.showTags(type,idTarget);
				} else {
					this.objFavourite.attr('title', ls.lang.get('talk_favourite_add'));
					this.hideTags(type,idTarget);
				}

				result.iCount > 0 ? counter.show().text(result.iCount) : counter.hide();

				ls.hook.run('ls_favourite_toggle_after',[idTarget,objFavourite,type,params,result],this);
			}
		}.bind(this));
		return false;
	};

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

	/**
	 * Save user tags
	 * 
	 * @param  {Object} form
	 */
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