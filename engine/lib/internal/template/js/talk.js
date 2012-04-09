var ls = ls || {};

/**
* Функционал личных сообщений
*/
ls.talk = (function ($) {
	
	this.addToTalkUser = function(aUser, idTalk){
		if(list.length == 0) {
			list = $('<ul class="list" id="speaker_list"></ul>');
			$('#speaker_list_block').append(list);
		}
		var listItem = $('<li id="speaker_item_'+aUser.sUserId+'_area"><a href="'+aUser.sUserLink+'" class="user">'+aUser.sUserLogin+'</a> - <a href="#" id="speaker_item_'+aUser.sUserId+'" class="delete">'+ls.lang.get('delete')+'</a></li>');
		list.append(listItem);
		ls.hook.run('ls_talk_add_to_talk_item_after',[idTalk,aUser],listItem);
	};
	
	/**
	* Добавляет пользователя к переписке
	*/
	this.addToTalk = function(idTalk){
		var sUsers = $('#talk_speaker_add').val();
		if(!sUsers) return false;
		$('#talk_speaker_add').val('');
		
		var url = aRouter['talk']+'ajaxaddtalkuser/';
		var params = {users: sUsers, idTalk: idTalk};
		
		'*addToTalkBefore*'; '*/addToTalkBefore*';
		ls.ajax(url, params, function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				$.each(result.aUsers, function(index, item) {
					if(item.bStateError){
						ls.msg.notice(null, item.sMsg);
					} else {
						ls.talk.addToTalkUser(item,idTalk);
					}
				});

				ls.hook.run('ls_talk_add_to_talk_after',[idTalk,result]);
			}
		});
		return false;
	};

	/**
	* Удаляет пользователя из переписки
	*/
	this.removeFromTalk = function(link, idTalk) {
		link = $(link);
		
		$('#'+link.attr('id')+'_area').fadeOut(500,function(){
			$(this).remove();
		});
		var idTarget = link.attr('id').replace('speaker_item_','');
		
		var url = aRouter['talk']+'ajaxdeletetalkuser/';
		var params = {idTarget: idTarget, idTalk: idTalk};

		'*removeFromTalkBefore*'; '*/removeFromTalkBefore*';
		ls.ajax(url, params, function(result) {
			if (!result) {
				ls.msg.error('Error','Please try again later');
				link.parent('li').show();
			}
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
				link.parent('li').show();
			}
			ls.hook.run('ls_talk_remove_from_talk_after',[idTalk,idTarget],link);
		});

		return false;
	};
	
	this.addToBlackListUser = function(aUser) {
		var list = $('#black_list');
		if(list.length == 0) {
			list = $('<ul class="list" id="black_list"></ul>');
			$('#black_list_block').append(list);
		}
		var listItem = $('<li id="blacklist_item_'+aUser.sUserId+'_area"><a href="#" class="user">'+aUser.sUserLogin+'</a> - <a href="#" id="blacklist_item_'+aUser.sUserId+'" class="delete">'+ls.lang.get('delete')+'</a></li>');
		$('#black_list').append(listItem);
		ls.hook.run('ls_talk_add_to_black_list_item_after',[aUser],listItem);
	};
	
	/**
	* Добавляет пользователя в черный список
	*/
	this.addToBlackList = function() {
		var sUsers = $('#talk_blacklist_add').val();
		if(!sUsers) return false;
		$('#talk_blacklist_add').val('');
		
		var url = aRouter['talk']+'ajaxaddtoblacklist/';
		var params = {users: sUsers};

		'*addToBlackListBefore*'; '*/addToBlackListBefore*';
		ls.ajax(url, params, function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				$.each(result.aUsers, function(index, item) {
					if(item.bStateError){
						ls.msg.notice(null, item.sMsg);
					} else {
						ls.talk.addToBlackListUser(item);
					}
				});
				ls.hook.run('ls_talk_add_to_black_list_after',[result]);
			}
		});
		return false;
	};
	
	/**
	* Удаляет пользователя из черного списка
	*/
	this.removeFromBlackList = function(link) {
		link = $(link);
		
		$('#'+link.attr('id')+'_area').fadeOut(500,function(){
			$(this).remove();
		});
		var idTarget = link.attr('id').replace('blacklist_item_','');

		var url = aRouter['talk']+'ajaxdeletefromblacklist/';
		var params = {idTarget: idTarget};
		
		'*removeFromBlackListBefore*'; '*/removeFromBlackListBefore*';
		ls.ajax(url, params, function(result) {
			if (!result) {
				ls.msg.error('Error','Please try again later');
				link.parent('li').show();
			}
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
				link.parent('li').show();
			}
			ls.hook.run('ls_talk_remove_from_black_list_after',[idTarget],link);
		});
		return false;
	};
	
	/**
	* Добавляет или удаляет друга из списка получателей
	*/
	this.toggleRecipient = function(login, add) {
		var to = $.map($('#talk_users').val().split(','), function(item, index){
			item = $.trim(item);
			return item != '' ? item : null;
		});
		if (add) { to.push(login); to = $.richArray.unique(to); } else { to = $.richArray.without(to, login); }
		$('#talk_users').val(to.join(', '));
	};
	
	return this;
}).call(ls.talk || {},jQuery);
