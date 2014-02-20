/**
 * Управление пользователями
 *
 * @module ls/user
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

ls.user = (function ($) {

	this.jcropImage = null;

	/**
	 * Инициализация
	 */
	this.init = function() {
		var self = this;

		/* Авторизация */
		ls.ajax.form(aRouter.login + 'ajax-login', '.js-form-login', function (result, status, xhr, form) {
            result.sUrlRedirect && (window.location = result.sUrlRedirect);
            ls.hook.run('ls_user_login_after', [form, result]);
		});

		/* Регистрация */
		ls.ajax.form(aRouter.registration + 'ajax-registration', '.js-form-registration', function (result, status, xhr, form) {
            result.sUrlRedirect && (window.location = result.sUrlRedirect);
            ls.hook.run('ls_user_registration_after', [form, result]);
		});

		/* Регистрация Modal */
		ls.ajax.form(aRouter.registration + 'ajax-registration', '.js-form-signup', function (result, status, xhr, form) {
            result.sUrlRedirect && (window.location = result.sUrlRedirect);
            ls.hook.run('ls_user_registration_after', [form, result]);
		});

		/* Восстановление пароля */
		ls.ajax.form(aRouter.login + 'ajax-reminder', '.js-form-recovery', function (result, status, xhr, form) {
            result.sUrlRedirect && (window.location = result.sUrlRedirect);
            ls.hook.run('ls_user_recovery_after', [form, result]);
		});

		/* Повторный запрос на ссылку активации */
		ls.ajax.form(aRouter.login + 'ajax-reactivation', '.js-form-reactivation', function (result, status, xhr, form) {
            form.find('input').val('');
            ls.hook.run('ls_user_reactivation_after', [form, result]);
		});

		$('.js-modal-toggle-registration').on('click', function (e) {
			$('[data-tab-target=tab-pane-registration]').tab('activate');
			ls.captcha.update();
			$('#modal-login').modal('show');
			e.preventDefault();
		});

		$('.js-modal-toggle-login').on('click', function (e) {
			$('[data-tab-target=tab-pane-login]').tab('activate');
			$('#modal-login').modal('show');
			e.preventDefault();
		});

		// Добавление пользователя в свою активность
		$('.js-user-follow').on('click', function (e) {
			var oElement = $(this);

			if (oElement.hasClass(ls.options.classes.states.active)) {
				// Удаляем
				ls.user_list_add.remove('activity', null, oElement.data('user-id'), function (oResponse, iUserId) {
					oElement.removeClass(ls.options.classes.states.active).text(ls.lang.get('profile_user_follow'));
				});
			} else {
				// Добавляем
				ls.user_list_add.add('activity', null, oElement.data('user-id'), {
					add_success: function (oResponse) {
						oElement.addClass(ls.options.classes.states.active).text(ls.lang.get('profile_user_unfollow'));
					}
				});
			}

			e.preventDefault();
		});

		// Добавление выбранных пользователей
		$(document).on('click', '.js-user-list-select-add', function (e) {
			var aCheckboxes = $('.js-user-list-select').find('.js-user-list-small-checkbox:checked'),
				oInput      = $( $(this).data('target') ),
				oInputValue = oInput.val();

			// Получаем логины для добавления
			var aLoginsAdd = $.map(aCheckboxes, function(oElement, iIndex) {
				return $(oElement).data('user-login');
			});

			// Получаем логины которые уже прописаны
			var aLoginsOld = $.map(oInputValue.split(','), function(sLogin, iIndex) {
				return $.trim(sLogin) || null;
			});

			// Мержим логины
			oInput.val( $.richArray.unique($.merge(aLoginsOld, aLoginsAdd)).join(', ') );

			$('#modal-users-select').modal('hide');
		});

		// Загрузка фотографии в профиль
		$('.js-ajax-user-photo-upload').on('change', function () {
			self.uploadProfilePhoto($(this));
		});
		// Удаление фотографии профиля
		$('.js-ajax-user-photo-upload-remove').on('click', function () {
			self.removeProfilePhoto($(this).data('userId'));
			return false;
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
		ls.ajax.load(url, params, function(result){
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
		ls.ajax.load(url, params, function(result) {
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
		ls.ajax.load(url, params, function(result){
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
	this.searchUsers = function(sFormSelector) {
		var url = aRouter['people']+'ajax-search/',
			oInputSearch = $(sFormSelector).find('input'),
			oOriginalContainer = $('#users-list-original'),
			oSearchContainer = $('#users-list-search');

		oInputSearch.addClass(ls.options.classes.states.loading);

		ls.hook.marker('searchUsersBefore');

		ls.ajax.submit(url, sFormSelector, function(result) {
			oInputSearch.removeClass(ls.options.classes.states.loading);

			if (result.bShowOriginal) {
				oSearchContainer.hide();
				oOriginalContainer.show();
			} else {
				oOriginalContainer.hide();
				oSearchContainer.html(result.sText).show();

				ls.hook.run('ls_user_search_users_after', [sFormSelector, result]);
			}
		});
	};


	this.addComplaint = function(form) {
		ls.ajax.submit(aRouter.profile+'ajax-complaint-add/', form, function(result){
			$('#modal-complaint-user').modal('hide');
		});
	};


	this.uploadProfilePhoto = function(input) {
		var form = $('<form method="post" enctype="multipart/form-data"></form>').hide().appendTo('body');
		input.clone(true).insertAfter(input);
		input.appendTo(form);
		$('<input type="hidden" name="user_id" value="'+input.data('userId')+'" >').appendTo(form);

		ls.ajax.submit(aRouter.settings+'ajax-upload-photo/', form, function (data) {
			if (data.bStateError) {
				ls.msg.error(data.sMsgTitle,data.sMsg);
			} else {
				$('.js-ajax-user-photo-image').attr('src',data.sFile);
				$('.js-ajax-user-photo-upload-choose').html(data.sChooseText);
				$('.js-ajax-user-photo-upload-remove').show();
			}
			form.remove();
		}.bind(this));
	};

	this.removeProfilePhoto = function(idUser) {
		var params = {user_id: idUser};

		ls.ajax.load(aRouter.settings+'ajax-remove-photo/', params, function(result) {
			if (result.bStateError) {
				ls.msg.error(null,result.sMsg);
			} else {
				$('.js-ajax-user-photo-image').attr('src',result.sFile);
				$('.js-ajax-user-photo-upload-choose').html(result.sChooseText);
				$('.js-ajax-user-photo-upload-remove').hide();
			}
		});
		return false;
	};

	return this;
}).call(ls.user || {},jQuery);
