var talk = {
	//==================
	// Функции
	//==================

	// Добавляет пользователя к переписке
	addToTalk: function(idTalk) {
		sUsers = $('#talk_speaker_add').val();
		if(!sUsers) return false;
		$('#talk_speaker_add').val('');
		thisObj = this;

		$.post(aRouter['talk']+'ajaxaddtalkuser/', { users: sUsers, idTalk: idTalk, security_ls_key: LIVESTREET_SECURITY_KEY }, function(result) {
			if (result.bStateError) {
				$.notifier.error(null, result.sMsg);
			} else {
				$.each(result.aUsers, function(index, item) {
					if(item.bStateError){
						$.notifier.notice(null, result.sMsg);
					} else {
						if($('#speaker_list').length == 0) {
							$('#speaker_list_block').append($('<ul class="list" id="speaker_list"></ul>'));
						}
						$('#speaker_list').append($('<li><a href="'+item.sUserLink+'" class="user">'+item.sUserLogin+'</a> - <a href="#" id="speaker_item_'+item.sUserId+'" class="delete">'+LANG_DELETE+'</a></li>'));
					}
				});
			}
		});
		
		return false;
	},
	
	
	// Удаляет пользователя из переписки
	deleteFromTalk: function(link, idTalk) {
		link = $(link);
		
		link.parent('li').fadeOut(500);
		idTarget = link.attr('id').replace('speaker_item_','');

		$.post(aRouter['talk']+'ajaxdeletetalkuser/', { idTarget: idTarget, idTalk: idTalk, security_ls_key: LIVESTREET_SECURITY_KEY }, function(result) {
			if (!result) {
				$.notifier.error('Error','Please try again later');
				link.parent('li').show();
			}
			if (result.bStateError) {
				$.notifier.error(null, result.sMsg);
				link.parent('li').show();
			}
		});
			
		return false;
	},
	
	
	// Добавляет пользователя в черный список
	addToBlackList: function() {
		sUsers = $('#talk_blacklist_add').val();
		if(!sUsers) return false;
		$('#talk_blacklist_add').val('');
		thisObj = this;

		$.post(aRouter['talk']+'ajaxaddtoblacklist/', { users: sUsers, security_ls_key: LIVESTREET_SECURITY_KEY }, function(result) {
			if (result.bStateError) {
				$.notifier.error(null, result.sMsg);
			} else {
				$.each(result.aUsers, function(index, item) {
					if(item.bStateError){
						$.notifier.notice(null, result.sMsg);
					} else {
						if($('#black_list').length == 0) {
							$('#black_list_block').append($('<ul class="list" id="black_list"></ul>'));
						}
						$('#black_list').append($('<li><a href="#" class="user">'+item.sUserLogin+'</a> - <a href="#" id="blacklist_item_'+item.sUserId+'" class="delete">'+LANG_DELETE+'</a></li>'));
					}
				});
			}
		});
		
		return false;
	},
	
	
	// Удаляет пользователя из черного списка
	deleteFromBlackList: function(link) {
		link = $(link);
		
		link.parent('li').fadeOut(500);
		idTarget = link.attr('id').replace('blacklist_item_','');

		$.post(aRouter['talk']+'ajaxdeletefromblacklist/', { idTarget: idTarget, security_ls_key: LIVESTREET_SECURITY_KEY }, function(result) {
			if (!result) {
				$.notifier.error('Error','Please try again later');
				link.parent('li').show();
			}
			if (result.bStateError) {
				$.notifier.error(null, result.sMsg);
				link.parent('li').show();
			}
		});
			
		return false;
	},
	

	// Добавляет или удаляет друга из списка получателей
	toggleRecipient: function(login, add) {
		to = $.map($('#talk_users').val().split(','), function(item, index){
			item = $.trim(item); 
			return item != '' ? item : null;
		});
		if (add) { to.push(login); to = $.richArray.unique(to); } else { to = $.richArray.without(to, login); }
		$('#talk_users').val(to.join(', '));
	},
}




$(document).ready(function(){
	// Добавляем или удаляем друга из списка получателей
	$('#friends input:checkbox').change(function(){
		talk.toggleRecipient($(this).parent().text(), $(this).attr('checked'));
	});
	
	// Добавляем всех друзей в список получателей
	$('#friend_check_all').click(function(){
		$('#friends input:checkbox').each(function(index, item){
			talk.toggleRecipient($(item).parent().text(), true);
			$(item).attr('checked', true);
		});
	});
	
	// Удаляем всех друзей из списка получателей
	$('#friend_uncheck_all').click(function(){
		$('#friends input:checkbox').each(function(index, item){
			talk.toggleRecipient($(item).parent().text(), false);
			$(item).attr('checked', false);
		});
	});
	
	// Удаляем пользователя из черного списка
	$("#black_list_block").delegate("a.delete", "click", function(){
		talk.deleteFromBlackList(this);
	});
	
	// Удаляем пользователя из переписки
	$("#speaker_list_block").delegate("a.delete", "click", function(){
		talk.deleteFromTalk(this, $('#talk_id').val());
	});
});