var blogs = {
	//==================
	// Функции
	//==================
	
	// Вступить или покинуть блог
	ajaxJoinLeaveBlog: function(obj, idBlog) {
		obj = $(obj);
		
		$.post(aRouter['blog']+'ajaxblogjoin/', { idBlog: idBlog, security_ls_key: LIVESTREET_SECURITY_KEY }, function(result) {
			if (result.bStateError) {
				$.notifier.error(null, result.sMsg);
			} else {
				$.notifier.notice(null, result.sMsg);
				obj.text(LANG_JOIN);
				if (result.bState) {
					obj.text(LANG_LEAVE);
				}
				$('#blog_user_count_'+idBlog).text(result.iCountUser);
			}
		});
	},

	
	// Отправляет приглашение вступить в блог
	addBlogInvite: function(idBlog) {
		sUsers = $('#blog_admin_user_add').val();
		if(!sUsers) return false;
		thisObj = this;
		
		$('#blog_admin_user_add').val('');

		$.post(aRouter['blog']+'ajaxaddbloginvite/', { users: sUsers, idBlog: idBlog, security_ls_key: LIVESTREET_SECURITY_KEY }, function(result) {
			if (result.bStateError) {
				$.notifier.error(null, result.sMsg);
			} else {
				$.each(result.aUsers, function(index, item) {
					if(item.bStateError){
						$.notifier.notice(null, result.sMsg);
					} else {
						if($('#invited_list').length == 0) {
							$('#invited_list_block').append($('<ul class="list" id="invited_list"></ul>'));
						}
						$('#invited_list').append($('<li><a href="'+item.sUserWebPath+'" class="user">'+item.sUserLogin+'</a></li>'));
					}
				});
			}
		});
		
		return false;
	},


	// Повторно отправляет приглашение
	reBlogInvite: function(idUser,idBlog) {
		$.post(aRouter['blog']+'ajaxrebloginvite/', { idUser: idUser, idBlog: idBlog, security_ls_key: LIVESTREET_SECURITY_KEY }, function(result){
			if (result.bStateError) {
				$.notifier.error(null, result.sMsg);
			} else {
				$.notifier.notice(null, result.sMsg);
			}
		});
		
		return false;
	}
}