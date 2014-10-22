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

		// TODO: Перенести в модуль auth

		/* Авторизация */
		$('.js-auth-login-form').on('submit', function (e) {
			ls.ajax.submit(aRouter.login + 'ajax-login', $(this), function ( response ) {
				response.sUrlRedirect && (window.location = response.sUrlRedirect);
			});

			e.preventDefault();
		});

		/* Регистрация */
		$('.js-auth-registration-form').on('submit', function (e) {
			ls.ajax.submit(aRouter.registration + 'ajax-registration', $(this), function ( response ) {
				response.sUrlRedirect && (window.location = response.sUrlRedirect);
			});

			e.preventDefault();
		});

		/* Восстановление пароля */
		$('.js-auth-reset-form').on('submit', function (e) {
			ls.ajax.submit(aRouter.login + 'ajax-reset', $(this), function ( response ) {
				response.sUrlRedirect && (window.location = response.sUrlRedirect);
			});

			e.preventDefault();
		});

		/* Повторный запрос на ссылку активации */
		ls.ajax.form(aRouter.login + 'ajax-reactivation', '.js-form-reactivation', function (result, status, xhr, form) {
            form.find('input').val('');
            ls.hook.run('ls_user_reactivation_after', [form, result]);
		});

		$('.js-modal-toggle-registration').on('click', function (e) {
			$('.js-auth-tab-reg').lsTab('activate');
			$('#modal-login').modal('show');
			e.preventDefault();
		});

		$('.js-modal-toggle-login').on('click', function (e) {
			$('.js-auth-tab-login').lsTab('activate');
			$('#modal-login').modal('show');
			e.preventDefault();
		});

		// Добавление пользователя в свою активность
		$('.js-user-follow').lsUserFollow({
			urls: {
				follow:   aRouter['stream'] + 'ajaxadduser/',
				unfollow: aRouter['stream'] + 'ajaxremoveuser/'
			}
		});

		// Добавление пользователя в свою активность
		$('.js-user-friend').lsUserFriend({
			urls: {
				add:    aRouter.profile + 'ajaxfriendadd/',
				remove: aRouter.profile + 'ajaxfrienddelete/',
				accept: aRouter.profile + 'ajaxfriendaccept/',
				modal:  aRouter.profile + 'ajax-modal-add-friend'
			}
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

		//
		// НАСТРОЙКИ
		//

		// Управление кастомными полями
		$( '.js-user-fields' ).lsUserFields();
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
                this.cropProfilePhoto(data.sTmpFile, input.data('userId'));
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
        }.bind(this));
		return false;
	};

    this.cropProfilePhoto = function(src,idUser) {
        this.showCropImage(src,{
            crop_params : {
                minSize: [100, 150]
            },
            save_params : {
                user_id : idUser
            },
            save_url : aRouter.settings+'ajax-crop-photo/',
            save_callback : function(result, modal, image, previews) {
                $('.js-ajax-user-photo-image').attr('src',result.sFile);
                $('.js-ajax-user-photo-upload-choose').html(result.sChooseText);
                $('.js-ajax-user-photo-upload-remove').show();
                $('.js-ajax-user-avatar-change').show();
            },
            modal_close_callback : function(e, modal) {
                ls.ajax.load(aRouter.settings+'ajax-crop-cancel-photo/', { user_id: idUser });
            }
        });
        return false;
    };

	this.changeProfileAvatar = function(idUser) {
        this.showCropImage($('.js-ajax-user-photo-image').attr('src'),{
            crop_w : 150,
            crop_h : 150,
            crop_params : {
                minSize: [32, 32],
                aspectRatio: 1
            },
            save_params : {
                user_id : idUser
            },
            save_url : aRouter.settings+'ajax-change-avatar/'
        });
		return false;
	};

    this.showCropImage = function(src, params) {
        params = params || {};
        var _this = this;
        ls.modal.load(aRouter.ajax+'modal/image-crop/', {image_src: src }, {
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

                _this.jcropImage && _this.jcropImage.destroy();

                image.css({
                    'width': 'auto',
                    'height': 'auto'
                });

                var cropParams={
                    minSize: [32, 32],
                    aspectRatio: 0,
                    onChange: showPreview,
                    onSelect: showPreview
                };

                cropParams = $.extend({}, cropParams, params.crop_params || { });

                image.Jcrop(cropParams, function () {
                    _this.jcropImage = this;
                    var iw=image.innerWidth();
                    var ih=image.innerHeight();

                    var sw = 0, sh = 0, ew = iw, eh = ih;

                    if (params.crop_w) {
                        if (!params.crop_h) {
                            params.crop_h = params.crop_w;
                        }

                        sw=iw/2-params.crop_w/2;
                        sh=ih/2-params.crop_h/2;
                        sw=sw>0 ? Math.round(sw) : 0;
                        sh=sh>0 ? Math.round(sh) : 0;

                        ew=sw+params.crop_w;
                        eh=sh+params.crop_h;
                    }

                    this.setSelect([sw, sh, ew, eh]);
                });

                submit.on('click',function() {
                    var paramsRequest={
                        size: _this.jcropImage.tellSelect(),
                        canvas_width: image.innerWidth()
                    };
                    paramsRequest = $.extend({}, paramsRequest, params.save_params || { });
                    ls.ajax.load(params.save_url, paramsRequest, function(result) {
                        if (result.bStateError) {
                            ls.msg.error(null,result.sMsg);
                        } else {
                            modal.hide();
                            if (params.save_callback) {
                                params.save_callback(result, modal, image, previews);
                            }
                        }
                    });
                });
            },
            afterhide: function( e, modal ) {
                if (params.modal_close_callback) {
                    params.modal_close_callback(e, modal);
                }
            },
            center: false
        });
        return false;
    };

	return this;
}).call(ls.user || {}, jQuery);
