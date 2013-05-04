/**
 * Активность
 */

var ls = ls || {};

ls.stream =( function ($) {
	this.isBusy = false;
	this.dateLast = null;

	this.options = {
		selectors: {
			userList:       'js-activity-block-users',
			userListId:     'activity-block-users',
			inputId:        'activity-block-users-input',
			noticeId:       'activity-block-users-notice',
			userListItemId: 'activity-block-users-item-'
		},
		elements: {
			userItem: function (element) {
				return $('<li id="' + ls.stream.options.selectors.userListItemId + element.uid + '">' +
							 '<input type="checkbox" ' + 
							        'class="input-checkbox" ' +
							        'data-user-id="' + element.uid + '" ' +
							        'checked="checked" />' +
							        '<a href="' + element.user_web_path + '">' + element.user_login + '</a>' +
						 '</li>');
			}
		}
	}

	/**
	 * Init
	 */
	this.init = function () {
		var self = this;

		$('.' + this.options.selectors.userList).on('change', 'input[type=checkbox]', function () {
			var userId = $(this).data('user-id');

			$(this).prop('checked') ? self.subscribe(userId) : self.unsubscribe(userId);	
		});

		$('#' + this.options.selectors.inputId).keydown(function (event) {
			event.which == 13 && ls.stream.appendUser();
		});
	};

	/**
	 * Подписаться на пользователя
	 * @param  {Number} iUserId ID пользователя
	 */
	this.subscribe = function (iUserId) {
		var self = this,
			url = aRouter['stream'] + 'subscribe/',
			params = { 'id': iUserId };

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

	/**
	 * Отписаться от пользователя
	 * @param  {Number} iUserId ID пользователя
	 */
	this.unsubscribe = function (iUserId) {
		var self = this,
			url = aRouter['stream'] + 'unsubscribe/',
			params = { 'id': iUserId };

		ls.hook.marker('unsubscribeBefore');

		ls.ajax(url, params, function(data) {
			if (!data.bStateError) {
				ls.msg.notice(data.sMsgTitle,data.sMsg);
				ls.hook.run('ls_stream_unsubscribe_after',[params,data]);
			}
		});
	};

	/**
	 * Подписаться на пользователя
	 */
	this.appendUser = function() {
		var self = this,
			sLogin = $('#' + self.options.selectors.inputId).val();

		if ( ! sLogin ) return;

		ls.hook.marker('appendUserBefore');

		ls.ajax(aRouter['stream'] + 'subscribeByLogin/', { 'login' : sLogin }, function(data) {
			if ( ! data.bStateError ) {
				var checkbox = $('.' + self.options.selectors.userList).find('input[data-user-id=' + data.uid + ']');

				$('#' + self.options.selectors.noticeId).remove();

				if (checkbox.length) {
					if (checkbox.prop("checked")) {
						ls.msg.error(ls.lang.get('error'), ls.lang.get('stream_subscribes_already_subscribed'));
					} else {
						checkbox.prop("checked", true);
						ls.msg.notice(data.sMsgTitle,data.sMsg);
					}
				} else {
					$('#' + self.options.selectors.inputId).autocomplete('close').val('');
					$('#' + self.options.selectors.userListId).show().append(self.options.elements.userItem(data));
					ls.msg.notice(data.sMsgTitle,data.sMsg);
				}

				ls.hook.run('ls_stream_append_user_after',[checkbox.length,data]);
			} else {
				ls.msg.error(data.sMsgTitle, data.sMsg);
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

	this.getMore = function () {
		if (this.isBusy) {
			return;
		}
		var lastId = $('#stream_last_id').val();
		if (!lastId) return;
		$('#stream_get_more').addClass('stream_loading');
		this.isBusy = true;

		var url = aRouter['stream']+'get_more/';
		var params = {'last_id':lastId,'date_last':this.dateLast};

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
		var params = {'last_id':lastId,'date_last':this.dateLast};

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
		var params = {'last_id':lastId, user_id: iUserId,'date_last':this.dateLast};

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