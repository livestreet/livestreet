var ls = ls || {};

/**
 * Стена пользователя
 */
ls.wall = (function ($) {

	this.options = {
		login: ''
	};

	this.iIdForReply=null;
	/**
	 * Добавление записи
	 */
	this.add = function(sText, iPid) {
		var url = aRouter['profile']+this.options.login+'/wall/add/';
		var params = {sText: sText, iPid: iPid};
		'*addBefore*'; '*/addBefore*';
		ls.ajax(url, params, function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				this.loadNew();
				ls.hook.run('ls_wall_add_after',[sText, iPid, result]);
			}
		}.bind(this));
		return false;
	};

	this.addReply = function(sText) {
		var url = aRouter['profile']+this.options.login+'/wall/add/';
		var params = {sText: sText, iPid: this.iIdForReply};
		'*addReplyBefore*'; '*/addReplyBefore*';
		ls.ajax(url, params, function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				$('#wall-reply-text').val('');
				$('#wall-reply-form').hide();
				this.loadReplyNew(this.iIdForReply);
				ls.hook.run('ls_wall_addreply_after',[sText, this.iIdForReply, result]);
			}
		}.bind(this));
		return false;
	};

	this.load = function(iIdLess,iIdMore,callback) {
		var url = aRouter['profile']+this.options.login+'/wall/load/';
		var params = {iIdLess: iIdLess ? iIdLess : '', iIdMore: iIdMore ? iIdMore : ''};
		'*loadBefore*'; '*/loadBefore*';
		ls.ajax(url, params, callback);
		return false;
	};

	this.loadReply = function(iIdLess,iIdMore,iPid,callback) {
		var url = aRouter['profile']+this.options.login+'/wall/load-reply/';
		var params = {iIdLess: iIdLess ? iIdLess : '', iIdMore: iIdMore ? iIdMore : '', iPid: iPid};
		'*loadReplyBefore*'; '*/loadReplyBefore*';
		ls.ajax(url, params, callback);
		return false;
	};

	this.loadNext = function() {
		var divLast=$('#wall-contener').find('.js-wall-item:last');
		if (divLast.length) {
			var idLess=divLast.attr('id').replace('wall-item-','');
		} else {
			return false;
		}
		this.load(idLess,'',function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				if (result.iCountWall) {
					$('#wall-contener').append(result.sText);
				}
				var iCount=result.iCountWall-result.iCountWallReturn;
				if (iCount) {
					$('#wall-count-next').text(iCount);
				} else {
					$('#wall-button-next').detach();
				}
				ls.hook.run('ls_wall_loadnext_after',[idLess, result]);
			}
		}.bind(this));
		return false;
	};

	this.loadNew = function() {
		var divFirst=$('#wall-contener').find('.js-wall-item:first');
		if (divFirst.length) {
			var idMore=divFirst.attr('id').replace('wall-item-','');
		} else {
			var idMore=-1;
		}
		this.load('',idMore,function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				if (result.iCountWall) {
					$('#wall-contener').prepend(result.sText);
				}
				ls.hook.run('ls_wall_loadnew_after',[idMore, result]);
			}
		}.bind(this));
		return false;
	};

	this.loadReplyNew = function(iPid) {
		var divFirst=$('#wall-reply-contener-'+iPid).find('.js-wall-reply-item::first');
		if (divFirst.length) {
			var idMore=divFirst.attr('id').replace('wall-reply-item-','');
		} else {
			var idMore=-1;
		}
		this.loadReply('',idMore,iPid,function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				if (result.iCountWall) {
					$('#wall-reply-contener-'+iPid).prepend(result.sText);
				}
				ls.hook.run('ls_wall_loadreplynew_after',[iPid, idMore, result]);
			}
		}.bind(this));
		return false;
	};

	this.loadReplyNext = function(iPid) {
		var divLast=$('#wall-reply-contener-'+iPid).find('.js-wall-reply-item:last');
		if (divLast.length) {
			var idLess=divLast.attr('id').replace('wall-reply-item-','');
		} else {
			return false;
		}
		this.loadReply(idLess,'',iPid,function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				if (result.iCountWall) {
					$('#wall-reply-contener-'+iPid).append(result.sText);
				}
				var iCount=result.iCountWall-result.iCountWallReturn;
				if (iCount) {
					$('#wall-reply-count-next-'+iPid).text(iCount);
				} else {
					$('#wall-reply-button-next-'+iPid).detach();
				}
				ls.hook.run('ls_wall_loadreplynext_after',[iPid, idLess, result]);
			}
		}.bind(this));
		return false;
	};

	this.toggleReply = function(iId) {
		var reply=$('#wall-reply-form');
		if (iId==this.iIdForReply) {
			reply.toggle();
		} else {
			reply.insertBefore($('#wall-reply-contener-'+iId));
			reply.show();
			this.iIdForReply=iId;
		}
		return false;
	};

	this.init = function(opt) {
		if (opt) {
			$.extend(true,this.options,opt);
		}
	};

	return this;
}).call(ls.wall || {},jQuery);