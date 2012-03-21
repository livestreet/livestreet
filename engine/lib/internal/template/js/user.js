var ls = ls || {};

/**
* Управление пользователями
*/
ls.user = (function ($) {

	this.jcropAvatar=null;
	this.jcropFoto=null;

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
		
		'*addFriendBefore*'; '*/addFriendBefore*';
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
				ls.hook.run('ls_user_add_friend_after', [idUser,sAction,result], obj);
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
		
		'*removeFriendBefore*'; '*/removeFriendBefore*';
		ls.ajax(url, params, function(result) {
			if (result.bStateError) {
				ls.msg.error(null,result.sMsg);
			} else {
				ls.msg.notice(null,result.sMsg);
				$('#delete_friend_item').remove();
				$('#profile_actions').prepend($(result.sToggleText));
				ls.hook.run('ls_user_remove_friend_after', [idUser,sAction,result], obj);
			}
		});
		return false;
	};

	/**
	 * Загрузка временной аватарки
	 * @param form
	 * @param input
	 */
	this.uploadAvatar = function(form,input) {
		if (!form && input) {
			var form = $('<form method="post" enctype="multipart/form-data"></form>').css({
				'display': 'none'
			}).appendTo('body');
			input.clone().appendTo(form);
		}

		ls.ajaxSubmit(aRouter['settings']+'profile/upload-avatar/',form,function(data){
			if (data.bStateError) {
				ls.msg.error(data.sMsgTitle,data.sMsg);
			} else {
				this.showResizeAvatar(data.sTmpFile);
			}
		}.bind(this));
	};

	/**
	 * Показывает форму для ресайза аватарки
	 * @param sImgFile
	 */
	this.showResizeAvatar = function(sImgFile) {
		if (this.jcropAvatar) {
			this.jcropAvatar.destroy();
		}
		$('#avatar-resize-original-img').attr('src',sImgFile+'?'+Math.random());
		$('#avatar-resize').show();
		var $this=this;
		$('#avatar-resize-original-img').Jcrop({
			aspectRatio: 1,
			minSize: [32,32]
		},function(){
			$this.jcropAvatar=this;
		});
	};

	/**
	 * Выполняет ресайз аватарки
	 */
	this.resizeAvatar = function() {
		if (!this.jcropAvatar) {
			return false;
		}
		var url = aRouter.settings+'profile/resize-avatar/';
		var params = {size: this.jcropAvatar.tellSelect()};

		'*resizeAvatarBefore*'; '*/resizeAvatarBefore*';
		ls.ajax(url, params, function(result) {
			if (result.bStateError) {
				ls.msg.error(null,result.sMsg);
			} else {
				$('#avatar-img').attr('src',result.sFile+'?'+Math.random());
				$('#avatar-resize').hide();
				$('#avatar-remove').show();
				$('#avatar-upload').text(result.sTitleUpload);
				ls.hook.run('ls_user_resize_avatar_after', [params, result]);
			}
		});

		return false;
	};

	/**
	 * Удаление аватарки
	 */
	this.removeAvatar = function() {
		var url = aRouter.settings+'profile/remove-avatar/';
		var params = {};

		'*removeAvatarBefore*'; '*/removeAvatarBefore*';
		ls.ajax(url, params, function(result) {
			if (result.bStateError) {
				ls.msg.error(null,result.sMsg);
			} else {
				$('#avatar-img').attr('src',result.sFile+'?'+Math.random());
				$('#avatar-remove').hide();
				$('#avatar-upload').text(result.sTitleUpload);
				ls.hook.run('ls_user_remove_avatar_after', [params, result]);
			}
		});

		return false;
	};

	/**
	 * Отмена ресайза аватарки, подчищаем временный данные
	 */
	this.cancelAvatar = function() {
		var url = aRouter.settings+'profile/cancel-avatar/';
		var params = {};

		'*cancelAvatarBefore*'; '*/cancelAvatarBefore*';
		ls.ajax(url, params, function(result) {
			if (result.bStateError) {
				ls.msg.error(null,result.sMsg);
			} else {
				$('#avatar-resize').hide();
				ls.hook.run('ls_user_cancel_avatar_after', [params, result]);
			}
		});

		return false;
	};

	/**
	 * Загрузка временной фотки
	 * @param form
	 * @param input
	 */
	this.uploadFoto = function(form,input) {
		if (!form && input) {
			var form = $('<form method="post" enctype="multipart/form-data"></form>').css({
				'display': 'none'
			}).appendTo('body');
			input.clone().appendTo(form);
		}

		ls.ajaxSubmit(aRouter['settings']+'profile/upload-foto/',form,function(data){
			if (data.bStateError) {
				ls.msg.error(data.sMsgTitle,data.sMsg);
			} else {
				this.showResizeFoto(data.sTmpFile);
			}
		}.bind(this));
	};

	/**
	 * Показывает форму для ресайза фотки
	 * @param sImgFile
	 */
	this.showResizeFoto = function(sImgFile) {
		if (this.jcropFoto) {
			this.jcropFoto.destroy();
		}
		$('#foto-resize-original-img').attr('src',sImgFile+'?'+Math.random());
		$('#foto-resize').show();
		var $this=this;
		$('#foto-resize-original-img').Jcrop({
			minSize: [32,32]
		},function(){
			$this.jcropFoto=this;
		});
	};

	/**
	 * Выполняет ресайз фотки
	 */
	this.resizeFoto = function() {
		if (!this.jcropFoto) {
			return false;
		}
		var url = aRouter.settings+'profile/resize-foto/';
		var params = {size: this.jcropFoto.tellSelect()};

		'*resizeFotoBefore*'; '*/resizeFotoBefore*';
		ls.ajax(url, params, function(result) {
			if (result.bStateError) {
				ls.msg.error(null,result.sMsg);
			} else {
				$('#foto-img').attr('src',result.sFile+'?'+Math.random());
				$('#foto-resize').hide();
				$('#foto-remove').show();
				$('#foto-upload').text(result.sTitleUpload);
				ls.hook.run('ls_user_resize_foto_after', [params, result]);
			}
		});

		return false;
	};

	/**
	 * Удаление фотки
	 */
	this.removeFoto = function() {
		var url = aRouter.settings+'profile/remove-foto/';
		var params = {};

		'*removeFotoBefore*'; '*/removeFotoBefore*';
		ls.ajax(url, params, function(result) {
			if (result.bStateError) {
				ls.msg.error(null,result.sMsg);
			} else {
				$('#foto-img').attr('src',result.sFile+'?'+Math.random());
				$('#foto-remove').hide();
				$('#foto-upload').text(result.sTitleUpload);
				ls.hook.run('ls_user_remove_foto_after', [params, result]);
			}
		});

		return false;
	};

	/**
	 * Отмена ресайза фотки, подчищаем временный данные
	 */
	this.cancelFoto = function() {
		var url = aRouter.settings+'profile/cancel-foto/';
		var params = {};

		'*cancelFotoBefore*'; '*/cancelFotoBefore*';
		ls.ajax(url, params, function(result) {
			if (result.bStateError) {
				ls.msg.error(null,result.sMsg);
			} else {
				$('#foto-resize').hide();
				ls.hook.run('ls_user_cancel_foto_after', [params, result]);
			}
		});

		return false;
	};


	this.validateRegistrationFields = function(aFields) {
		var url = aRouter.registration+'ajax-validate-fields/';
		var params = {fields: aFields};

		'*validateRegistrationFieldsBefore*'; '*/validateRegistrationFieldsBefore*';
		ls.ajax(url, params, function(result) {
			$.each(aFields,function(i,aField){
				if (result.aErrors && result.aErrors[aField.field][0]) {
					$('#validate-error-'+aField.field).removeClass('validate-error-hide').addClass('validate-error-show').text(result.aErrors[aField.field][0]);
				} else {
					$('#validate-error-'+aField.field).removeClass('validate-error-show').addClass('validate-error-hide');
				}
			});
			ls.hook.run('ls_user_validate_registration_fields_after', [aFields, result]);
		});
	};

	this.validateRegistrationField = function(sField,sValue,aParams) {
		var aFields=[];
		aFields.push({field: sField, value: sValue, params: aParams || {}});
		this.validateRegistrationFields(aFields);
	};

	return this;
}).call(ls.user || {},jQuery);