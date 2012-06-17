var ls = ls || {};

/**
* JS функционал для блогов
*/
ls.blog = (function ($) {
	
	/**
	* Вступить или покинуть блог
	*/
	this.toggleJoin = function(obj, idBlog){
		var url = aRouter['blog']+'ajaxblogjoin/';
		var params = {idBlog: idBlog};
		
		ls.hook.marker('toggleJoinBefore');
		ls.ajax(url,params,function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				obj = $(obj);
				ls.msg.notice(null, result.sMsg);
				
				var text = result.bState
					? ls.lang.get('blog_leave')
					: ls.lang.get('blog_join')
				;
				
				obj.empty().text(text);
				obj.toggleClass('active');
				
				$('#blog_user_count_'+idBlog).text(result.iCountUser);
				ls.hook.run('ls_blog_toggle_join_after',[idBlog,result],obj);
			}
		});
	};

	/**
	* Отправляет приглашение вступить в блог
	*/
	this.addInvite = function(idBlog) {
		var sUsers = $('#blog_admin_user_add').val();
		if(!sUsers) return false;
		$('#blog_admin_user_add').val('');
		
		var url = aRouter['blog']+'ajaxaddbloginvite/';
		var params = {users: sUsers, idBlog: idBlog};
		
		ls.hook.marker('addInviteBefore');
		ls.ajax(url, params, function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				$.each(result.aUsers, function(index, item) {
					if(item.bStateError){
						ls.msg.error(null, item.sMsg);
					} else {
						if($('#invited_list').length == 0) {
							$('#invited_list_block').append($('<ul class="list" id="invited_list"></ul>'));
						}
						var listItem = $('<li><a href="'+item.sUserWebPath+'" class="user">'+item.sUserLogin+'</a></li>');
						$('#invited_list').append(listItem);
						ls.hook.run('ls_blog_add_invite_user_after',[idBlog,item],listItem);
					}
				});
				ls.hook.run('ls_blog_add_invite_after',[idBlog,sUsers,result]);
			}
		});
		
		return false;
	};

	/**
	* Повторно отправляет приглашение
	*/
	this.repeatInvite = function(idUser,idBlog) {
		var url = aRouter['blog']+'ajaxrebloginvite/';
		var params = {idUser: idUser, idBlog: idBlog};
		
		ls.hook.marker('repeatInviteBefore');
		ls.ajax(url, params, function(result){
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				ls.msg.notice(null, result.sMsg);
				ls.hook.run('ls_blog_repeat_invite_after',[idUser,idBlog,result]);
			}
		});
		
		return false;
	};

	/**
	 * Удаляет приглашение в блог
	 */
	this.removeInvite = function(idUser,idBlog) {
		var url = aRouter['blog']+'ajaxremovebloginvite/';
		var params = {idUser: idUser, idBlog: idBlog};

		ls.hook.marker('removeInviteBefore');
		ls.ajax(url, params, function(result){
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				$('#blog-invite-remove-item-'+idBlog+'-'+idUser).remove();
				ls.msg.notice(null, result.sMsg);
				ls.hook.run('ls_blog_remove_invite_after',[idUser,idBlog,result]);
			}
		});

		return false;
	};
	
	/**
	* Отображение информации о блоге
	*/
	this.loadInfo = function(idBlog) {
		var url = aRouter['blog']+'ajaxbloginfo/';
		var params = {idBlog: idBlog};
		
		ls.hook.marker('loadInfoBefore');
		ls.ajax(url, params, function(result){
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				var block = $('#block_blog_info');
				block.html(result.sText);
				ls.hook.run('ls_blog_load_info_after',[idBlog,result],block);
			}
		});
	};
	
	/**
	* Отображение информации о типе блога
	*/
	this.loadInfoType = function(type) {
		$('#blog_type_note').text($('#blog_type_note_'+type).text());
	};

	/**
	 * Поиск блогов
	 */
	this.searchBlogs = function(form) {
		var url = aRouter['blogs']+'ajax-search/';
		var inputSearch=$('#'+form).find('input');
		inputSearch.addClass('loader');

		ls.hook.marker('searchBlogsBefore');
		ls.ajaxSubmit(url, form, function(result){
			inputSearch.removeClass('loader');
			if (result.bStateError) {
				$('#blogs-list-search').hide();
				$('#blogs-list-original').show();
			} else {
				$('#blogs-list-original').hide();
				$('#blogs-list-search').html(result.sText).show();
				ls.hook.run('ls_blog_search_blogs_after',[form, result]);
			}
		});
	};

	/**
	 * Показать подробную информацию о блоге
	 */
	this.toggleInfo = function() {
		$('#blog-more-content').slideToggle();
		var more = $('#blog-more');
		more.toggleClass('expanded');
		
		if(more.hasClass('expanded')) {
			more.html(ls.lang.get('blog_fold_info'));
		} else {
			more.html(ls.lang.get('blog_expand_info'));
		}
		
		return false;
	};

	return this;
}).call(ls.blog || {},jQuery);