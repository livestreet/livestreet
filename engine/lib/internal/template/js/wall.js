var ls = ls || {};

/**
 * Стена пользователя
 */
ls.wall = (function ($) {

	this.options = {
		login: '',
		id_less: '',
		id_more: ''
	};

	this.iIdLess=null;
	this.iIdMore=null;
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

	this.load = function(iIdLess,iIdMore,collback) {
		var url = aRouter['profile']+this.options.login+'/wall/load/';
		var params = {iIdLess: iIdLess ? iIdLess : '', iIdMore: iIdMore ? iIdMore : ''};
		'*loadBefore*'; '*/loadBefore*';
		ls.ajax(url, params, collback);
		return false;
	};

	this.loadNext = function() {
		this.load(this.iIdLess,'',function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				if (result.iWallIdLess) {
					this.iIdLess=result.iWallIdLess;
				}
				if (result.iCountWall) {
					$('#wall-contener').append(result.sText);
				}
				var iCount=result.iCountWall-result.iCountWallReturn;
				if (iCount) {
					$('#wall-count-next').text(iCount);
				} else {
					$('#wall-button-next').detach();
				}
				ls.hook.run('ls_wall_loadnext_after',[this.iIdLess, result]);
			}
		}.bind(this));
		return false;
	};

	this.loadNew = function() {
		this.load('',this.iIdMore,function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				if (result.iWallIdMore) {
					this.iIdMore=result.iWallIdMore;
				}
				if (result.iCountWall) {
					$('#wall-contener').prepend(result.sText);
				}
				ls.hook.run('ls_wall_loadnew_after',[this.iIdLess, result]);
			}
		}.bind(this));
		return false;
	};

	this.init = function(opt) {
		if (opt) {
			$.extend(true,this.options,opt);
		}
		this.iIdLess=this.options.id_less;
		this.iIdMore=this.options.id_more;
	};

	return this;
}).call(ls.wall || {},jQuery);