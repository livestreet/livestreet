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
		
		ls.ajax(this.options.type[type].url, params, function(result) {
			$(this).trigger('toggle',[idTarget,objFavourite,type,params,result]);
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				ls.msg.notice(null, result.sMsg);
				this.objFavourite.removeClass(this.options.active);
				if (result.bState) {
					this.objFavourite.addClass(this.options.active);
				}
			}
		}.bind(this));
		return false;
	}

	return this;
}).call(ls.favourite || {},jQuery);