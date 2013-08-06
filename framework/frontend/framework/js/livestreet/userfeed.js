/**
 * Лента
 */

var ls = ls || {};

ls.userfeed = (function ($) {
	this.isBusy = false;
	
	this.options = {
		selectors: {
			userList:       'js-userfeed-block-users',
			userListId:     'userfeed-block-users',
			inputId:        'userfeed-block-users-input',
			noticeId:       'userfeed-block-users-notice',
			userListItemId: 'userfeed-block-users-item-'
		},
		elements: {
			userItem: function (element) {
				return ls.stream.options.elements.userItem(element);
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

			$(this).prop('checked') ? self.subscribe('users', userId) : self.unsubscribe('users', userId);
		});

		$('#' + this.options.selectors.inputId).keydown(function (event) {
			event.which == 13 && ls.userfeed.appendUser();
		});
	};
	
	this.subscribe = function (sType, iId) {
		var url = aRouter['feed']+'subscribe/';
		var params = {'type':sType, 'id':iId};
		
		ls.hook.marker('subscribeBefore');
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
		
		ls.hook.marker('unsubscribeBefore');
		ls.ajax(url, params, function(data) { 
			if (!data.bStateError) {
				ls.msg.notice(data.sMsgTitle,data.sMsg);
				ls.hook.run('ls_userfeed_unsubscribe_after',[sType, iId, data]);
			}
		});
	}
	
	this.appendUser = function() {
		var self = this,
			sLogin = $('#' + self.options.selectors.inputId).val();

		if ( ! sLogin ) return;
		
		ls.hook.marker('appendUserBefore');

		ls.ajax(aRouter['feed']+'subscribeByLogin/', {'login':sLogin}, function(data) {
			if (data.bStateError) {
				ls.msg.error(data.sMsgTitle,data.sMsg);
			} else {
				var checkbox = $('.' + self.options.selectors.userList).find('input[data-user-id=' + data.uid + ']');

				$('#' + self.options.selectors.noticeId).remove();

				if (checkbox.length) {
					if (checkbox.prop('checked')) {
						ls.msg.error(data.lang_error_title,data.lang_error_msg);
						return;
					} else {
						checkbox.prop('checked', true);
						ls.msg.notice(data.sMsgTitle,data.sMsg);
					}
				} else {
					$('#' + self.options.selectors.inputId).autocomplete('close').val('');
					$('#' + self.options.selectors.userListId).append(self.options.elements.userItem(data));
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
		$('#userfeed_get_more').addClass('loading');
		this.isBusy = true;
		
		var url = aRouter['feed']+'get_more/';
		var params = {'last_id':lastId};
		
		ls.hook.marker('getMoreBefore');
		ls.ajax(url, params, function(data) {
			if (!data.bStateError && data.topics_count) {
				$('#userfeed_loaded_topics').append(data.result);
				$('#userfeed_last_id').attr('value', data.iUserfeedLastId);
			}
			if (!data.topics_count) {
				$('#userfeed_get_more').hide();
			}
			$('#userfeed_get_more').removeClass('loading');
			ls.hook.run('ls_userfeed_get_more_after',[lastId, data]);
			this.isBusy = false;
		}.bind(this));
	}
	
	return this;
}).call(ls.userfeed || {},jQuery);