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
	"use strict";

	/**
	 * Дефолтные опции
	 *
	 * @private
	 */
	var _defaults = {
		urls: {
			follow: aRouter['stream'] + 'ajaxadduser/',
			unfollow: aRouter['stream'] + 'ajaxremoveuser/'
		}
	};

	this.jcropImage = null;

	/**
	 * Инициализация
	 */
	this.init = function(options) {
		var _this = this;

		this.options = $.extend({}, _defaults, options);

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
				_this.unfollow(oElement, oElement.data('user-id'));
			} else {
				_this.follow(oElement, oElement.data('user-login'));
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
			_this.uploadProfilePhoto($(this));
		});
		// Удаление фотографии профиля
		$('.js-ajax-user-photo-upload-remove').on('click', function () {
			_this.removeProfilePhoto($(this).data('userId'));
			return false;
		});
		// Изменения аватара
		$('.js-ajax-user-avatar-change').on('click', function () {
			_this.changeProfileAvatar($(this).data('userId'));
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
	 * Подписка на пользователя
	 */
	this.follow = function(oElement, sUserLogin) {
		ls.ajax.load(this.options.urls.follow, { aUserList: [ sUserLogin ] }, function(oResponse) {
			oElement.addClass(ls.options.classes.states.active).text( ls.lang.get('profile_user_unfollow') );
		}.bind(this));
	};

	/**
	 * Отписаться от пользователя
	 */
	this.unfollow = function(oElement, iUserId) {
		ls.ajax.load(this.options.urls.unfollow, { iUserId: iUserId }, function(oResponse) {
			oElement.removeClass(ls.options.classes.states.active).text( ls.lang.get('profile_user_follow') );
		}.bind(this));
	};

	/**
	 * Добавляет жалобу
	 */
	this.addComplaint = function(oForm) {
		ls.ajax.submit(aRouter.profile + 'ajax-complaint-add/', oForm, function(result) {
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
				$('.js-ajax-user-avatar-change').show();
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
				$('.js-ajax-user-avatar-change').hide();
			}
		});
		return false;
	};

	this.changeProfileAvatar = function(idUser) {
		var _this = this;
		ls.modal.load(aRouter.ajax+'modal/image-crop/', {image_src: $('.js-ajax-user-photo-image').attr('src') }, {
			aftershow: function( e, modal ) {
				var image    = modal.element.find('.js-crop-image'),
					previews = modal.element.find('.js-crop-preview-image'),
					submit   = modal.element.find('.js-ajax-image-crop-submit');

				function showPreview( coords ) {
					previews.each(function() {
						var preview = $( this ),
							size = preview.data('size'),
							rx = size / coords.w,
							ry = size / coords.h;

						preview.css({
							width:      Math.round( rx * image.width() ) + 'px',
							height:     Math.round( ry * image.height() ) + 'px',
							marginLeft: '-' + Math.round( rx * coords.x ) + 'px',
							marginTop:  '-' + Math.round( ry * coords.y ) + 'px'
						});
					})
				}

				this.jcropImage && this.jcropImage.destroy();

				image.css({
					'width': 'auto',
					'height': 'auto'
				});

				image.Jcrop({
					minSize: [32, 32],
					aspectRatio: 1,
					onChange: showPreview,
					onSelect: showPreview
				}, function () {
					_this.jcropImage = this;
					var w=image.innerWidth();
					var h=image.innerHeight();

					w=w/2-75;
					h=h/2-75;
					w=w>0 ? Math.round(w) : 0;
					h=h>0 ? Math.round(h) : 0;
					this.setSelect([w, h, w+150, h+150]);
				});

				submit.on('click',function() {
					var params={
						user_id: idUser,
						size: _this.jcropImage.tellSelect(),
						canvas_width: image.innerWidth()
					}
					ls.ajax.load(aRouter.settings+'ajax-change-avatar/', params, function(result) {
						if (result.bStateError) {
							ls.msg.error(null,result.sMsg);
						} else {
							modal.hide();
						}
					});
				});
			},
			center: false
		});
		return false;
	};

	return this;
}).call(ls.user || {}, jQuery);
