/**
 * Подписка
 *
 * @module ls/subscribe
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

ls.subscribe = (function ($) {

	/**
	 * Подписка/отписка
	 */
	this.toggle = function(targetType, targetId, mail, value) {
		var url = aRouter['subscribe']+'ajax-subscribe-toggle/';
		var params = { target_type: targetType, target_id: targetId, mail: mail, value: value };

		ls.hook.marker('toggleBefore');

		ls.ajax.load( url, params, function( response ) {
			ls.hook.run('ls_subscribe_toggle_after',[targetType, targetId, mail, value, response ]);
		});

		return false;
	}

	return this;
}).call(ls.subscribe || {}, jQuery);