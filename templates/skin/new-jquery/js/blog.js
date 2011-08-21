var ls = ls || {};

/**
* JS функционал для блогов
*/
ls.blog = (function ($) {
	
	/**
	* Вступить или покинуть блог
	*/
	this.toggleJoin = function(obj, idBlog){
		ls.ajax(aRouter['blog']+'ajaxblogjoin/',{idBlog: idBlog},function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				obj = $(obj);
				ls.msg.notice(null, result.sMsg);
				obj.removeClass("active");
				if (result.bState) {
					obj.addClass("active");
				}
				$('#blog_user_count_'+idBlog).text(result.iCountUser);
			}
		});
	};

	/**
	* Отправляет приглашение вступить в блог
	*/
	this.addInvite = function(idBlog) {
		sUsers = $('#blog_admin_user_add').val();
		if(!sUsers) return false;
		$('#blog_admin_user_add').val('');

		ls.ajax(aRouter['blog']+'ajaxaddbloginvite/', {users: sUsers, idBlog: idBlog}, function(result) {
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
						$('#invited_list').append($('<li><a href="'+item.sUserWebPath+'" class="user">'+item.sUserLogin+'</a></li>'));
					}
				});
			}
		});
		
		return false;
	}

	/**
	* Повторно отправляет приглашение
	*/
	this.repeatInvite = function(idUser,idBlog) {
		ls.ajax(aRouter['blog']+'ajaxrebloginvite/', {idUser: idUser, idBlog: idBlog}, function(result){
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				ls.msg.notice(null, result.sMsg);
			}
		});
		
		return false;
	}
	
	/**
	* Отображение информации о блоге
	*/
	this.loadInfo = function(idBlog) {
		ls.ajax(aRouter['blog']+'ajaxbloginfo/', {idBlog: idBlog}, function(result){
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				$('#block_blog_info').html(result.sText);
			}
		});
	}
	
	/**
	* Отображение информации о типе блога
	*/
	this.loadInfoType = function(type) {
		$('#blog_type_note').text($('#blog_type_note_'+type).text());
	}
	
	return this;
}).call(ls.blog || {},jQuery);