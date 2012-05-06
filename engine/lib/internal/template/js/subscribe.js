var ls = ls || {};

/**
 * Подписка
 */
ls.subscribe = (function ($) {

	/**
	 * Подписка/отписка
	 */
	this.toggle = function(sTargetType, iTargetId, sMail, iValue) {
		var url = aRouter['subscribe']+'ajax-subscribe-toggle/';
		var params = {target_type: sTargetType, target_id: iTargetId, mail: sMail, value: iValue};
		ls.hook.marker('toggleBefore');
		ls.ajax(url, params, function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				ls.msg.notice(null, result.sMsg);
				ls.hook.run('ls_subscribe_toggle_after',[sTargetType, iTargetId, sMail, iValue, result]);
			}
		});
		return false;
	}

	return this;
}).call(ls.subscribe || {},jQuery);