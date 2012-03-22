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
				$('.js-wall-reply-parent-text').val('');
				this.loadNew();
				ls.hook.run('ls_wall_add_after',[sText, iPid, result]);
			}
		}.bind(this));
		return false;
	};

	this.addReply = function(sText, iPid) {
		var url = aRouter['profile']+this.options.login+'/wall/add/';
		var params = {sText: sText, iPid: iPid};
		'*addReplyBefore*'; '*/addReplyBefore*';
		ls.ajax(url, params, function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				$('.js-wall-reply-text').val('');
				this.loadReplyNew(iPid);
				ls.hook.run('ls_wall_addreply_after',[sText, iPid, result]);
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
		var divLast=$('#wall-container').find('.js-wall-item:last');
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
					$('#wall-container').append(result.sText);
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
		var divFirst=$('#wall-container').find('.js-wall-item:first');
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
					$('#wall-container').prepend(result.sText);
				}
				ls.hook.run('ls_wall_loadnew_after',[idMore, result]);
			}
		}.bind(this));
		return false;
	};

	this.loadReplyNew = function(iPid) {
		var divFirst=$('#wall-reply-container-'+iPid).find('.js-wall-reply-item::last');
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
					$('#wall-reply-container-'+iPid).append(result.sText);
				}
				ls.hook.run('ls_wall_loadreplynew_after',[iPid, idMore, result]);
			}
		}.bind(this));
		return false;
	};

	this.loadReplyNext = function(iPid) {
		var divLast=$('#wall-reply-container-'+iPid).find('.js-wall-reply-item:first');
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
					$('#wall-reply-container-'+iPid).prepend(result.sText);
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
		$('#wall-item-' + iId + ' .wall-submit-reply').toggle();
		return false;
	};

	this.expandReply = function(iId) {
		$('#wall-item-' + iId + ' .wall-submit-reply').addClass('active');
		return false;
	};

	this.init = function(opt) {
		if (opt) {
			$.extend(true,this.options,opt);
		}
	};

	return this;
}).call(ls.wall || {},jQuery);


jQuery(document).ready(function($){
	$(document).click(function() {
		$('.wall-submit-reply.active').removeClass('active');
	});
	
	$('body').on("click", ".wall-submit-reply", function(e) { 
		e.stopPropagation();
	});
});