var ls = ls || {};

/**
 * Управление пользователями
 * TODO: Вынести аякс загрузку изображений в отдельный модуль
 */
ls.user = (function ($) {

	this.jcropImage = null;

	/**
	 * Инициализация
	 */
	this.init = function() {
		var self = this;

		/* Авторизация */
		ls.ajaxForm(aRouter.login + 'ajax-login', '.js-form-login', function (result, status, xhr, form) {
            result.sUrlRedirect && (window.location = result.sUrlRedirect);
            ls.hook.run('ls_user_login_after', [form, result]);
		});

		/* Регистрация */
		ls.ajaxForm(aRouter.registration + 'ajax-registration', '.js-form-signup', function (result, status, xhr, form) {
            result.sUrlRedirect && (window.location = result.sUrlRedirect);
            ls.hook.run('ls_user_registration_after', [form, result]);
		});

		/* Восстановление пароля */
		ls.ajaxForm(aRouter.login + 'ajax-reminder', '.js-form-recovery', function (result, status, xhr, form) {
            result.sUrlRedirect && (window.location = result.sUrlRedirect);
            ls.hook.run('ls_user_recovery_after', [form, result]);
		});

		/* Повторный запрос на ссылку активации */
		ls.ajaxForm(aRouter.login + 'ajax-reactivation', '.js-form-reactivation', function (result, status, xhr, form) {
            form.find('input').val('');
            ls.hook.run('ls_user_reactivation_after', [form, result]);
		});

		/* Аякс загрузка изображений */
		this.ajaxUploadImageInit({
			selectors: {
				element: '.js-ajax-avatar-upload'
			},
			cropOptions: {
				aspectRatio: 1
			},
			urls: {
				upload: aRouter['settings'] + 'profile/upload-avatar/',
				remove: aRouter['settings'] + 'profile/remove-avatar/',
				cancel: aRouter['settings'] + 'profile/cancel-avatar/',
				crop:   aRouter['settings'] + 'profile/resize-avatar/'
			}
		});

		this.ajaxUploadImageInit({
			selectors: {
				element: '.js-ajax-photo-upload'
			},
			urls: {
				upload: aRouter['settings'] + 'profile/upload-foto/',
				remove: aRouter['settings'] + 'profile/remove-foto/',
				cancel: aRouter['settings'] + 'profile/cancel-foto/',
				crop:   aRouter['settings'] + 'profile/resize-foto/'
			}
		});

		$('.js-ajax-image-upload-crop-cancel').on('click', function (e) {
			self.ajaxUploadImageCropCancel();
		});

		$('.js-ajax-image-upload-crop-submit').on('click', function (e) {
			self.ajaxUploadImageCropSubmit();
		});
	};

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

		ls.hook.marker('addFriendBefore');
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
				$('#profile_actions').prepend($($.trim(result.sToggleText)));
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

		ls.hook.marker('removeFriendBefore');
		ls.ajax(url, params, function(result) {
			if (result.bStateError) {
				ls.msg.error(null,result.sMsg);
			} else {
				ls.msg.notice(null,result.sMsg);
				$('#delete_friend_item').remove();
				$('#profile_actions').prepend($($.trim(result.sToggleText)));
				ls.hook.run('ls_user_remove_friend_after', [idUser,sAction,result], obj);
			}
		});
		return false;
	};

	/**
	 * Поиск пользователей по началу логина
	 */
	this.searchUsersByPrefix = function(sPrefix,obj) {
		obj=$(obj);
		var url = aRouter['people']+'ajax-search/';
		var params = {user_login: sPrefix, isPrefix: 1};
		$('#search-user-login').addClass('loader');

		ls.hook.marker('searchUsersByPrefixBefore');
		ls.ajax(url, params, function(result){
			$('#search-user-login').removeClass('loader');
			$('#user-prefix-filter').find('.active').removeClass('active');
			obj.parent().addClass('active');
			if (result.bStateError) {
				$('#users-list-search').hide();
				$('#users-list-original').show();
			} else {
				$('#users-list-original').hide();
				$('#users-list-search').html(result.sText).show();
				ls.hook.run('ls_user_search_users_by_prefix_after',[sPrefix, obj, result]);
			}
		});
		return false;
	};

	/**
	 * Подписка
	 */
	this.followToggle = function(obj, iUserId) {
		if ($(obj).hasClass('followed')) {
			ls.stream.unsubscribe(iUserId);
			$(obj).toggleClass('followed').text(ls.lang.get('profile_user_follow'));
		} else {
			ls.stream.subscribe(iUserId);
			$(obj).toggleClass('followed').text(ls.lang.get('profile_user_unfollow'));
		}
		return false;
	};

	/**
	 * Поиск пользователей
	 */
	this.searchUsers = function(form) {
		var url = aRouter['people']+'ajax-search/';
		var inputSearch=$('#'+form).find('input');
		inputSearch.addClass('loader');

		ls.hook.marker('searchUsersBefore');
		ls.ajaxSubmit(url, form, function(result){
			inputSearch.removeClass('loader');
			if (result.bStateError) {
				$('#users-list-search').hide();
				$('#users-list-original').show();
			} else {
				$('#users-list-original').hide();
				$('#users-list-search').html(result.sText).show();
				ls.hook.run('ls_user_search_users_after',[form, result]);
			}
		});
	};



	/**
	 * Загрузка временной аватарки
	 * @param form
	 * @param input
	 */
	this.ajaxUploadImageInit = function(options) {
		var self = this;

		var defaults = {
			cropOptions: {
				minSize: [32, 32]
			},
			selectors: {
				element: '.js-ajax-image-upload',
				image: '.js-ajax-image-upload-image',
				image_crop: '.js-image-crop',
				remove_button: '.js-ajax-image-upload-remove',
				choose_button: '.js-ajax-image-upload-choose',
				input_file: '.js-ajax-image-upload-file',
				crop_cancel_button: '.js-ajax-image-upload-crop-cancel',
				crop_submit_button: '.js-ajax-image-upload-crop-submit'
			},
			urls: {
				upload: aRouter['settings'] + 'profile/upload-avatar/',
				remove: aRouter['settings'] + 'profile/remove-avatar/',
				cancel: aRouter['settings'] + 'profile/cancel-avatar/',
				crop:   aRouter['settings'] + 'profile/resize-avatar/'
			}
		};

		var options = $.extend(true, {}, defaults, options);

		$(options.selectors.element).each(function () {
			var $element = $(this);

			var elements = {
				element: $element,
				remove_button:  $element.find(options.selectors.remove_button),
				choose_button:  $element.find(options.selectors.choose_button),
				image:  $element.find(options.selectors.image),
				image_crop:  $element.find(options.selectors.image_crop)
			};

			$element.find(options.selectors.input_file).on('change', function () {
				self.currentElements = elements;
				self.currentOptions = options;
				self.ajaxUploadImage(null, $(this), options);
			});

			elements.remove_button.on('click', function (e) {
				self.ajaxUploadImageRemove(options, elements);
				e.preventDefault();
			});
		});
	};

	/**
	 * Загрузка временной аватарки
	 * @param form
	 * @param input
	 */
	this.ajaxUploadImage = function(form, input, options) {
		if ( ! form && input ) {
			var form = $('<form method="post" enctype="multipart/form-data"></form>').hide().appendTo('body');

			input.clone(true).insertAfter(input);
			input.appendTo(form);
		}

		ls.ajaxSubmit(options.urls.upload, form, function (data) {
			if (data.bStateError) {
				ls.msg.error(data.sMsgTitle,data.sMsg);
			} else {
				this.ajaxUploadImageModalCrop(data.sTmpFile, options);
			}
			form.remove();
		}.bind(this));
	};

	/**
	 * Показывает форму для ресайза аватарки
	 * @param sImgFile
	 */
	this.ajaxUploadImageModalCrop = function(sImgFile, options) {
		var self = this;

		this.jcropImage && this.jcropImage.destroy();

		$('.js-image-crop').attr('src', sImgFile + '?' + Math.random()).css({
			'width': 'auto',
			'height': 'auto'
		});

		if ($('#modal-image-crop').length)
			$('#modal-image-crop').modal('show');
		else {
			ls.debug('Error [Ajax Image Upload]:\nМодальное окно ресайза изображения не найдено');
		}

		$('.js-image-crop').Jcrop(options.cropOptions, function () {
			self.jcropImage = this;
			this.setSelect([0, 0, 500, 500]);
		});
	};

	/**
	 * Удаление аватарки
	 */
	this.ajaxUploadImageRemove = function(options, elements) {
		ls.hook.marker('removeAvatarBefore');

		ls.ajax(options.urls.remove, {}, function(result) {
			if (result.bStateError) {
				ls.msg.error(null,result.sMsg);
			} else {
				elements.image.attr('src', result.sFile + '?' + Math.random());
				elements.remove_button.hide();
				elements.choose_button.text(result.sTitleUpload);

				ls.hook.run('ls_user_remove_avatar_after', [result]);
			}
		});
	};

	/**
	 * Отмена ресайза аватарки, подчищаем временный данные
	 */
	this.ajaxUploadImageCropCancel = function() {
		ls.hook.marker('cancelAvatarBefore');

		ls.ajax(this.currentOptions.urls.cancel, {}, function(result) {
			if (result.bStateError) {
				ls.msg.error(null,result.sMsg);
			} else {
				$('#modal-image-crop').modal('hide');
				ls.hook.run('ls_user_cancel_avatar_after', [result]);
			}
		});
	};

	/**
	 * Выполняет ресайз аватарки
	 */
	this.ajaxUploadImageCropSubmit = function() {
		var self = this;

		if ( ! this.jcropImage ) {
			return false;
		}

		var params = {
			size: this.jcropImage.tellSelect()
		};

		ls.hook.marker('resizeAvatarBefore');

		ls.ajax(self.currentOptions.urls.crop, params, function(result) {
			if (result.bStateError) {
				ls.msg.error(null,result.sMsg);
			} else {
				self.currentElements.image.attr('src',result.sFile+'?'+Math.random());
				$('#modal-image-crop').modal('hide');
				self.currentElements.remove_button.show();
				self.currentElements.choose_button.text(result.sTitleUpload);

				ls.hook.run('ls_user_resize_avatar_after', [params, result]);
			}
		});

		return false;
	};


	return this;
}).call(ls.user || {},jQuery);