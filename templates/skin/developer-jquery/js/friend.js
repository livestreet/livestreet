function ajaxAddUserFriend(obj, idUser, sAction) {
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
	
	$.post(sPath, { idUser: idUser, userText: sText, security_ls_key: LIVESTREET_SECURITY_KEY }, function(result){
		if (!result) {
			$.notifier.error('Error','Please try again later');         
			$('#add_friend_form').children().each(function(i, item){$(item).removeAttr('disabled')});
		}
		if (result.bStateError) {
			$.notifier.error(null,result.sMsg);
			$('#add_friend_form').children().each(function(i, item){$(item).removeAttr('disabled')});
		} else {            	
			$.notifier.notice(null,result.sMsg);
			$('#add_friend_form').jqmHide();
			$('#add_friend_item').remove();
			$('#profile_actions').prepend($(result.sToggleText));           		
		}
	});
}

function ajaxDeleteUserFriend(obj,idUser,sAction) {   
	$.post(aRouter.profile+'ajaxfrienddelete/', { idUser: idUser,sAction: sAction, security_ls_key: LIVESTREET_SECURITY_KEY }, function(result) {
		if (result.bStateError) {
			$.notifier.error(null,result.sMsg);
		} else {            	
			$.notifier.notice(null,result.sMsg);
			$('#delete_friend_item').remove();            		
			$('#profile_actions').prepend($(result.sToggleText)); 
		}
	});
}
