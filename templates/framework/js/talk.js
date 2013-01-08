var ls = ls || {};

/**
* Функционал личных сообщений
*/
ls.talk = (function ($) {
	
	/**
	* Добавляет пользователя к переписке
	*/
	this.addToTalk = function(idTalk){
		var sUsers = $('#talk_speaker_add').val();
		if(!sUsers) return false;
		$('#talk_speaker_add').val('');
		
		var url = aRouter['talk']+'ajaxaddtalkuser/';
		var params = {users: sUsers, idTalk: idTalk};
		
		ls.hook.marker('addToTalkBefore');
		ls.ajax(url, params, function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				$.each(result.aUsers, function(index, item) {
					if(item.bStateError){
						ls.msg.notice(null, item.sMsg);
					} else {
						var list = $('#speaker_list');
						if(list.length == 0) {
							list = $('<ul class="list" id="speaker_list"></ul>');
							$('#speaker_list_block').append(list);
						}
						var listItem = $('<li id="speaker_item_'+item.sUserId+'_area"><a href="'+item.sUserLink+'" class="user">'+item.sUserLogin+'</a> - <a href="#" id="speaker_item_'+item.sUserId+'" class="delete">'+ls.lang.get('delete')+'</a></li>')
						list.append(listItem);
						ls.hook.run('ls_talk_add_to_talk_item_after',[idTalk,item],listItem);
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

		ls.hook.marker('removeFromTalkBefore');
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
	
	/**
	* Добавляет пользователя в черный список
	*/
	this.addToBlackList = function() {
		var sUsers = $('#talk_blacklist_add').val();
		if(!sUsers) return false;
		$('#talk_blacklist_add').val('');
		
		var url = aRouter['talk']+'ajaxaddtoblacklist/';
		var params = {users: sUsers};

		ls.hook.marker('addToBlackListBefore');
		ls.ajax(url, params, function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				$.each(result.aUsers, function(index, item) {
					if(item.bStateError){
						ls.msg.notice(null, item.sMsg);
					} else {
						var list = $('#black_list');
						if(list.length == 0) {
							list = $('<ul class="list" id="black_list"></ul>');
							$('#black_list_block').append(list);
						}
						var listItem = $('<li id="blacklist_item_'+item.sUserId+'_area"><a href="#" class="user">'+item.sUserLogin+'</a> - <a href="#" id="blacklist_item_'+item.sUserId+'" class="delete">'+ls.lang.get('delete')+'</a></li>');
						$('#black_list').append(listItem);
						ls.hook.run('ls_talk_add_to_black_list_item_after',[item],listItem);
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
		
		ls.hook.marker('removeFromBlackListBefore');
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

	/**
	 * Очищает поля фильтра
	 */
	this.clearFilter = function() {
		$('#block_talk_search_content').find('input[type="text"]').val('');
		$('#block_talk_search_content').find('input[type="checkbox"]').removeAttr("checked");
		return false;
	};

	/**
	 * Удаление списка писем
	 */
	this.removeTalks = function() {
		if ($('.form_talks_checkbox:checked').length == 0) {
			return false;
		}
		$('#form_talks_list_submit_del').val(1);
		$('#form_talks_list_submit_read').val(0);
		$('#form_talks_list').submit();
		return false;
	};

	/**
	 * Пометка о прочтении писем
	 */
	this.makeReadTalks = function() {
		if ($('.form_talks_checkbox:checked').length == 0) {
			return false;
		}
		$('#form_talks_list_submit_read').val(1);
		$('#form_talks_list_submit_del').val(0);
		$('#form_talks_list').submit();
		return false;
	};
	
	return this;
}).call(ls.talk || {},jQuery);
