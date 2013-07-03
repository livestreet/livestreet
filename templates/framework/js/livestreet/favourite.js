var ls = ls || {};

/**
* Добавление в избранное
*/
ls.favourite = (function ($) {
	/**
	* Опции
	*/
	this.options = {
		active: 'active',
		type: {
			topic: {
				url: 			aRouter['ajax']+'favourite/topic/',
				targetName: 	'idTopic'
			},
			talk: {
				url: 			aRouter['ajax']+'favourite/talk/',
				targetName: 	'idTalk'
			},
			comment: {
				url: 			aRouter['ajax']+'favourite/comment/',
				targetName: 	'idComment'
			}
		}
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

	this.showEditTags = function(idTarget,type,obj) {
		var form=$('#favourite-form-tags');
		$('#favourite-form-tags-target-type').val(type);
		$('#favourite-form-tags-target-id').val(idTarget);
		var text='';
		var tags=$('.js-favourite-tags-'+$('#favourite-form-tags-target-type').val()+'-'+$('#favourite-form-tags-target-id').val());
		tags.find('.js-favourite-tag-user a').each(function(k,tag){
			if (text) {
				text=text+', '+$(tag).text();
			} else {
				text=$(tag).text();
			}
		});
		$('#favourite-form-tags-tags').val(text);
		//$(obj).parents('.js-favourite-insert-after-form').after(form);
		form.jqmShow();

		return false;
	};

	this.hideEditTags = function() {
		$('#favourite-form-tags').jqmHide();
		return false;
	};

	this.saveTags = function(form) {
		var url=aRouter['ajax']+'favourite/save-tags/';
		ls.hook.marker('saveTagsBefore');
		ls.ajaxSubmit(url, $(form), function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				this.hideEditTags();
				var type=$('#favourite-form-tags-target-type').val();
				var tags=$('.js-favourite-tags-'+type+'-'+$('#favourite-form-tags-target-id').val());
				tags.find('.js-favourite-tag-user').detach();
				var edit=tags.find('.js-favourite-tag-edit');
				$.each(result.aTags,function(k,v){
					edit.before('<li class="'+type+'-tags-user js-favourite-tag-user">, <a rel="tag" href="'+v.url+'">'+v.tag+'</a></li>');
				});

				ls.hook.run('ls_favourite_save_tags_after',[form,result],this);
			}
		}.bind(this));
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