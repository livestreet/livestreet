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
			sText = $('#add_friend_text').val();
			$('#add_friend_form').children().each(function(i, item){$(item).attr('disabled','disabled')});
		} else {
			sText='';
		}

		if(sAction == 'accept') {
			sPath = aRouter.profile+'ajaxfriendaccept/';
		} else {
			sPath = aRouter.profile+'ajaxfriendadd/';
		}

		ls.ajax(sPath, {idUser: idUser, userText: sText}, function(result){
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
			}
		});
		return false;
	};

	/**
	* Удаление из друзей
	*/
	this.removeFriend = function(obj,idUser,sAction) {
		ls.ajax(aRouter.profile+'ajaxfrienddelete/', {idUser: idUser,sAction: sAction}, function(result) {
			if (result.bStateError) {
				ls.msg.error(null,result.sMsg);
			} else {
				ls.msg.notice(null,result.sMsg);
				$('#delete_friend_item').remove();
				$('#profile_actions').prepend($(result.sToggleText));
			}
		});
		return false;
	}
	
	return this;
}).call(ls.user || {},jQuery);