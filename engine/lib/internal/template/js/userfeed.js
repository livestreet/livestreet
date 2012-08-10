var ls = ls || {};

ls.userfeed =( function ($) {
	this.isBusy = false;
	
	this.subscribe = function (sType, iId) {
		var url = aRouter['feed']+'subscribe/';
		var params = {'type':sType, 'id':iId};
		
<<<<<<< HEAD
		ls.hook.marker('subscribeBefore');
=======
		ls.hook.marker('subscribeBefore'); ls.hook.marker('/subscribeBefore');
>>>>>>> branch 'master' of git@github.com:1d10t/livestreet.git
		ls.ajax(url, params, function(data) { 
			if (!data.bStateError) {
				ls.msg.notice(data.sMsgTitle,data.sMsg);
				ls.hook.run('ls_userfeed_subscribe_after',[sType, iId, data]);
			}
		});
	}
	
	this.unsubscribe = function (sType, iId) {
		var url = aRouter['feed']+'unsubscribe/';
		var params = {'type':sType, 'id':iId};
		
<<<<<<< HEAD
		ls.hook.marker('unsubscribeBefore');
=======
		ls.hook.marker('unsubscribeBefore'); ls.hook.marker('/unsubscribeBefore');
>>>>>>> branch 'master' of git@github.com:1d10t/livestreet.git
		ls.ajax(url, params, function(data) { 
			if (!data.bStateError) {
				ls.msg.notice(data.sMsgTitle,data.sMsg);
				ls.hook.run('ls_userfeed_unsubscribe_after',[sType, iId, data]);
			}
		});
	}
	
	this.appendUser = function() {
		var sLogin = $('#userfeed_users_complete').val();
		if (!sLogin) return;
		
		var url = aRouter['feed']+'subscribeByLogin/';
		var params = {'login':sLogin};
		
<<<<<<< HEAD
		ls.hook.marker('appendUserBefore');
=======
		ls.hook.marker('appendUserBefore'); ls.hook.marker('/appendUserBefore');
>>>>>>> branch 'master' of git@github.com:1d10t/livestreet.git
		ls.ajax(url, params, function(data) {
			if (data.bStateError) {
				ls.msg.error(data.sMsgTitle,data.sMsg);
			} else {
				$('#userfeed_no_subscribed_users').remove();
				var checkbox = $('#usf_u_'+data.uid);
				if (checkbox.length) {
					if (checkbox.attr('checked')) {
						ls.msg.error(data.lang_error_title,data.lang_error_msg);
						return;
					} else {
						checkbox.attr('checked', 'on');
						ls.msg.notice(data.sMsgTitle,data.sMsg);
					}
				} else {
					var liElement=$('<li><input type="checkbox" class="userfeedUserCheckbox input-checkbox" id="usf_u_'+data.uid+'" checked="checked" onClick="if ($(this).get(\'checked\')) {ls.userfeed.subscribe(\'users\','+data.uid+')} else {ls.userfeed.unsubscribe(\'users\','+data.uid+')}" /><a href="'+data.user_web_path+'">'+data.user_login+'</a></li>');
					$('#userfeed_block_users_list').append(liElement);
					ls.msg.notice(data.sMsgTitle,data.sMsg);
				}
			}
		});
	}
	
	this.getMore = function () {
		if (this.isBusy) {
			return;
		}
		var lastId = $('#userfeed_last_id').val();
		if (!lastId) return;
		$('#userfeed_get_more').addClass('userfeed_loading');
		this.isBusy = true;
		
		var url = aRouter['feed']+'get_more/';
		var params = {'last_id':lastId};
		
<<<<<<< HEAD
		ls.hook.marker('getMoreBefore');
=======
		ls.hook.marker('getMoreBefore'); ls.hook.marker('/getMoreBefore');
>>>>>>> branch 'master' of git@github.com:1d10t/livestreet.git
		ls.ajax(url, params, function(data) {
			if (!data.bStateError && data.topics_count) {
				$('#userfeed_loaded_topics').append(data.result);
				$('#userfeed_last_id').attr('value', data.iUserfeedLastId);
			}
			if (!data.topics_count) {
				$('#userfeed_get_more').hide();
			}
			$('#userfeed_get_more').removeClass('userfeed_loading');
			ls.hook.run('ls_userfeed_get_more_after',[lastId, data]);
			this.isBusy = false;
		}.bind(this));
	}
	
	return this;
}).call(ls.userfeed || {},jQuery);
