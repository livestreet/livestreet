/**
 * Активность
 */

var ls = ls || {};

ls.stream =( function ($) {
	this.isBusy = false;
	this.sDateLast = null;

	this.options = {
		selectors: {
			userList:       'js-activity-block-users',
			getMoreButton:  'activity-get-more',
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

		$('#' + this.options.selectors.getMoreButton).on('click', function () {
			self.getMore(this);
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

	/**
	 * Подгрузка событий
	 * @param  {Object} oGetMoreButton Кнопка
	 */
	this.getMore = function (oGetMoreButton) {
		if (this.isBusy) return;

		var $oGetMoreButton = $(oGetMoreButton),
			$oLastId = $('#activity-last-id');
			iLastId = $oLastId.val();

		if ( ! iLastId ) return;

		$oGetMoreButton.addClass('loading');
		this.isBusy = true;

		var params = $.extend({}, {
			'iLastId':   iLastId,
			'sDateLast': this.sDateLast
		}, ls.tools.getDataOptions($oGetMoreButton, 'param'));

		var url = aRouter['stream'] + 'get_more' + (params.type ? '_' + params.type : '') + '/';

		ls.hook.marker('getMoreBefore');

		ls.ajax(url, params, function(data) {
			if ( ! data.bStateError && data.events_count ) {
				$('#activity-event-list').append(data.result);
				$oLastId.attr('value', data.iStreamLastId);
			}

			if ( ! data.events_count) {
				$oGetMoreButton.hide();
			}

			$oGetMoreButton.removeClass('loading');

			ls.hook.run('ls_stream_get_more_after',[iLastId, data]);

			this.isBusy = false;
		}.bind(this));
	};

	return this;
}).call(ls.stream || {},jQuery);