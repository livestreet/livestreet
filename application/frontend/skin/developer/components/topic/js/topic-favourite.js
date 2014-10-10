/**
 * Приглашение пользователей в закрытый блог
 *
 * @module blog_invite_users
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
	"use strict";

	$.widget( "livestreet.lsTopicFavourite", $.livestreet.lsFavourite, {
		/**
		 * Дефолтные опции
		 */
		options: {
			urls: {
				toggle: aRouter['ajax'] + 'favourite/topic/'
			},
			aftertogglesuccess: function (e, data) {
				if (data.response.bState) {
					ls.tags && ls.tags.showPersonalTags('topic', data.context.options.params.iTargetId);
				} else {
					ls.tags && ls.tags.hidePersonalTags('topic', data.context.options.params.iTargetId);
				}
			}
		}
	});
})(jQuery);