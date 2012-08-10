var ls = ls || {};

/**
* Управление пользователями
*/
ls.user = (function ($) {
	
	/**
	* Добавление в друзья
	*/
	this.addFriend = function(obj, idUser, sAction){
		if(sAction != 'link' && sAction != 'accept') {
			var sText = $('#add_friend_text').val();
			$('#add_friend_form').children().each(function(i, item){$(item).attr('disabled','disabled')});
		} else {
			var sText='';
		}

		if(sAction == 'accept') {
			var url = aRouter.profile+'ajaxfriendaccept/';
		} else {
			var url = aRouter.profile+'ajaxfriendadd/';
		}
		
		var params = {idUser: idUser, userText: sText};
		
		ls.hook.marker('addFriendBefore'); ls.hook.marker('/addFriendBefore');
		ls.ajax(url, params, function(result){
			$('#add_friend_form').children().each(function(i, item){$(item).removeAttr('disabled')});
			if (!result) {
				ls.msg.error('Error','Please try again later');
			}
			if (result.bStateError) {
				ls.msg.error(null,result.sMsg);
			} else {
				ls.msg.notice(null,result.sMsg);
				$('#add_friend_form').jqmHide();
				$('#add_friend_item').remove();
				$('#profile_actions').prepend($(result.sToggleText));
				ls.hook.run('ls_friend_add_friend_after', [idUser,sAction,result], obj);
			}
		});
		return false;
	};

	/**
	* Удаление из друзей
	*/
	this.removeFriend = function(obj,idUser,sAction) {
		var url = aRouter.profile+'ajaxfrienddelete/';
		var params = {idUser: idUser,sAction: sAction};
		
		ls.hook.marker('removeFriendBefore'); ls.hook.marker('/removeFriendBefore');
		ls.ajax(url, params, function(result) {
			if (result.bStateError) {
				ls.msg.error(null,result.sMsg);
			} else {
				ls.msg.notice(null,result.sMsg);
				$('#delete_friend_item').remove();
				$('#profile_actions').prepend($(result.sToggleText));
				ls.hook.run('ls_friend_remove_friend_after', [idUser,sAction,result], obj);
			}
		});
		return false;
	}
	
	return this;
}).call(ls.user || {},jQuery);