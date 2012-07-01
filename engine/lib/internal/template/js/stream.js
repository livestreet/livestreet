var ls = ls || {};

ls.stream =( function ($) {
	this.isBusy = false;

	this.subscribe = function (iTargetUserId) {
		var url = aRouter['stream']+'subscribe/';
		var params = {'id':iTargetUserId};

		ls.hook.marker('subscribeBefore');
		ls.ajax(url, params, function(data) {
			if (data.bStateError) {
				ls.msg.error(data.sMsgTitle,data.sMsg);
			} else {
				ls.msg.notice(data.sMsgTitle,data.sMsg);
				ls.hook.run('ls_stream_subscribe_after',[params,data]);
			}
		});
	};

	this.unsubscribe = function (iId) {
		var url = aRouter['stream']+'unsubscribe/';
		var params = {'id':iId};

		ls.hook.marker('unsubscribeBefore');
		ls.ajax(url, params, function(data) {
			if (!data.bStateError) {
				ls.msg.notice(data.sMsgTitle,data.sMsg);
				ls.hook.run('ls_stream_unsubscribe_after',[params,data]);
			}
		});
	};

	this.switchEventType = function (iType) {
		var url = aRouter['stream']+'switchEventType/';
		var params = {'type':iType};

		ls.hook.marker('switchEventTypeBefore');
		ls.ajax(url, params, function(data) {
			if (!data.bStateError) {
				ls.msg.notice(data.sMsgTitle,data.sMsg);
				ls.hook.run('ls_stream_switch_event_type_after',[params,data]);
			}
		});
	};

	this.appendUser = function() {
		var sLogin = $('#stream_users_complete').val();
		if (!sLogin) return;

		var url = aRouter['stream']+'subscribeByLogin/';
		var params = {'login':sLogin};

		ls.hook.marker('appendUserBefore');
		ls.ajax(url, params, function(data) {
			if (!data.bStateError) {
				$('#stream_no_subscribed_users').remove();
				var checkbox = $('#strm_u_'+data.uid);
				if (checkbox.length) {
					if (checkbox.attr('checked')) {
						ls.msg.error(ls.lang.get('error'),ls.lang.get('stream_subscribes_already_subscribed'));
					} else {
						checkbox.attr('checked', 'on');
						ls.msg.notice(data.sMsgTitle,data.sMsg);
					}
				} else {
					var liElement=$('<li><input type="checkbox" class="streamUserCheckbox input-checkbox" id="strm_u_'+data.uid+'" checked="checked" onClick="if ($(this).get(\'checked\')) {ls.stream.subscribe('+data.uid+')} else {ls.stream.unsubscribe('+data.uid+')}" /> <a href="'+data.user_web_path+'">'+data.user_login+'</a></li>');
					$('#stream_block_users_list').append(liElement);
					ls.msg.notice(data.sMsgTitle,data.sMsg);
				}
				ls.hook.run('ls_stream_append_user_after',[checkbox.length,data]);
			} else {
				ls.msg.error(data.sMsgTitle,data.sMsg);
			}
		});
	};

	this.getMore = function () {
		if (this.isBusy) {
			return;
		}
		var lastId = $('#stream_last_id').val();
		if (!lastId) return;
		$('#stream_get_more').addClass('stream_loading');
		this.isBusy = true;

		var url = aRouter['stream']+'get_more/';
		var params = {'last_id':lastId};

		ls.hook.marker('getMoreBefore');
		ls.ajax(url, params, function(data) {
			if (!data.bStateError && data.events_count) {
				$('#stream-list').append(data.result);
				$('#stream_last_id').attr('value', data.iStreamLastId);
			}
			if (!data.events_count) {
				$('#stream_get_more').hide();
			}
			$('#stream_get_more').removeClass('stream_loading');
			ls.hook.run('ls_stream_get_more_after',[lastId, data]);
			this.isBusy = false;
		}.bind(this));
	};

	this.getMoreAll = function () {
		if (this.isBusy) {
			return;
		}
		var lastId = $('#stream_last_id').val();
		if (!lastId) return;
		$('#stream_get_more').addClass('stream_loading');
		this.isBusy = true;

		var url = aRouter['stream']+'get_more_all/';
		var params = {'last_id':lastId};

		ls.hook.marker('getMoreAllBefore');
		ls.ajax(url, params, function(data) {
			if (!data.bStateError && data.events_count) {
				$('#stream-list').append(data.result);
				$('#stream_last_id').attr('value', data.iStreamLastId);
			}
			if (!data.events_count) {
				$('#stream_get_more').hide();
			}
			$('#stream_get_more').removeClass('stream_loading');
			ls.hook.run('ls_stream_get_more_all_after',[lastId, data]);
			this.isBusy = false;
		}.bind(this));
	};

	this.getMoreByUser = function (iUserId) {
		if (this.isBusy) {
			return;
		}
		var lastId = $('#stream_last_id').val();
		if (!lastId) return;
		$('#stream_get_more').addClass('stream_loading');
		this.isBusy = true;

		var url = aRouter['stream']+'get_more_user/';
		var params = {'last_id':lastId, user_id: iUserId};

		ls.hook.marker('getMoreByUserBefore');
		ls.ajax(url, params, function(data) {
			if (!data.bStateError && data.events_count) {
				$('#stream-list').append(data.result);
				$('#stream_last_id').attr('value', data.iStreamLastId);
			}
			if (!data.events_count) {
				$('#stream_get_more').hide();
			}
			$('#stream_get_more').removeClass('stream_loading');
			ls.hook.run('ls_stream_get_more_by_user_after',[lastId, iUserId, data]);
			this.isBusy = false;
		}.bind(this));
	};

	return this;
}).call(ls.stream || {},jQuery);