/**
 * Блоги
 * 
 * @module ls/blogs
 * 
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

ls.blog = (function ($) {
	"use strict";

	/**
	 * Дефолтные опции
	 * 
	 * @private
	 */
	var _defaults = {
		// Роутеры
		routers: {
			join:       aRouter['blog'] + 'ajaxblogjoin/',
			categories: aRouter['ajax'] + 'blogs/get-by-category/',
			info:       aRouter['blog'] + 'ajaxbloginfo/',
			search:     aRouter['blogs'] + 'ajax-search/',
			invite: {
				add:    aRouter['blog'] + 'ajaxaddbloginvite/',
				remove: aRouter['blog'] + 'ajaxremovebloginvite/',
				repeat: aRouter['blog'] + 'ajaxrebloginvite/',
			}
		},

		// Селекторы
		selectors: {
			addBlogSelectType: '.js-blog-add-type',
			toggle_join: '.js-blog-join',
			users_number: '.js-blog-users-number',
			info: '.js-blog-info',
			blog_add_type_note: '#blog_type_note',
			invite: {
				form: {
					self:   '.js-blog-invite-form',
					users:  '.js-blog-invite-form-users',
					submit: '.js-blog-invite-form-submit',
				},
				container:   '.js-blog-invite-container',
				user_list:   '.js-blog-invite-users',
				user:        '.js-blog-invite-user',
				user_remove: '.js-blog-invite-user-remove',
				user_repeat: '.js-blog-invite-user-repeat',
			},
			nav: {
				categories: '.js-blog-nav-categories',
				blogs:      '.js-blog-nav-blogs',
				submit:     '.js-blog-nav-submit',
			}
		},

		// HTML
		html: {
			invite_item: function(iBlogId, aUser) {
				return '<li class="user-list-small-item js-blog-invite-user" data-blog-id="' + iBlogId + '" data-user-id="' + aUser.iUserId + '">' +
							'<div class="user-item">' +
								'<a href="' + aUser.sUserWebPath + '" class="user-item-avatar-link"><img src="' + aUser.sUserAvatar48 + '" class="user-item-avatar" width="24" /></a> ' +
								'<a href="' + aUser.sUserWebPath + '" class="user-item-name">' + aUser.sUserLogin + '</a> ' +
							'</div>' +
							'<div class="user-list-small-item-actions">' +
								'<a href="#" class="icon-repeat js-blog-invite-user-repeat" title=""></a> ' +
								'<a href="#" class="icon-remove js-blog-invite-user-remove" title=""></a>' +
							'</div>' +
						'</li>';
			}
		}
	};

	/**
	 * Инициализация
	 *
	 * @param  {Object} options Опции
	 */
	this.init = function(options) {
		var _this = this;

		this.options = $.extend({}, _defaults, options);

		this.elements = {
			invite: {
				form: {
					self:   $(this.options.selectors.invite.form.self),
					users:  $(this.options.selectors.invite.form.users),
					submit: $(this.options.selectors.invite.form.submit),
				},
				container: $(this.options.selectors.invite.container),
				user_list: $(this.options.selectors.invite.user_list),
				user:      $(this.options.selectors.invite.user),
			},
			nav: {
				categories: $(this.options.selectors.nav.categories),
				blogs:      $(this.options.selectors.nav.blogs),
				submit:     $(this.options.selectors.nav.submit),
			},
			info: $(this.options.selectors.info),
			toggle_join: $(this.options.selectors.toggle_join),
			blog_add_type_note: $(this.options.selectors.blog_add_type_note),
		};

		// Подгрузка информации о выбранном типе блога при создании блога
		$(this.options.selectors.addBlogSelectType).on('change', function (e) {
			_this.loadInfoType($(this).val());
		});

		// Вступить/покинуть блог
		this.elements.toggle_join.on('click', function (e) {
			_this.toggleJoin($(this), $(this).data('blog-id'));
			e.preventDefault();
		});

		/**
		 * Инвайты
		 */

		// Добавить инвайт
		this.elements.invite.form.self.on('submit', function (e) {
			_this.invite.add($(this).data('blog-id'), _this.elements.invite.form.users.val());
			e.preventDefault();
		});

		// Удалить инвайт
		$(document).on('click', this.options.selectors.invite.user_remove, function (e) {
			var oElement = $(this).closest(_this.options.selectors.invite.user);

			_this.invite.remove(oElement.data('user-id'), oElement.data('blog-id'));
			e.preventDefault();
		});

		// Повторно отправить инвайт
		$(document).on('click', this.options.selectors.invite.user_repeat, function (e) {
			var oElement = $(this).closest(_this.options.selectors.invite.user);

			_this.invite.repeat(oElement.data('user-id'), oElement.data('blog-id'));
			e.preventDefault();
		});

		/**
		 * Блок навигации по категориям и блогам
		 */

		// Подгрузка блогов из выбранной категории
		this.elements.nav.categories.on('change', function (e) {
			_this.loadBlogsByCategory($(this).val());
		});

		// Переход на страницу выбранного блога
		this.elements.nav.submit.on('click', function (e) {
			_this.navigatorGoSelectBlog();
		});
	};

	/**
	 * Вступить или покинуть блог
	 */
	this.toggleJoin = function(oToggle, iIdBlog) {
		var sUrl    = this.options.routers.join,
			oParams = { idBlog: iIdBlog };

		oToggle.addClass(ls.options.classes.states.loading);

		ls.hook.marker('toggleJoinBefore');

		ls.ajax.load(sUrl, oParams, function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				ls.msg.notice(null, result.sMsg);

				oToggle.empty().text( result.bState ? ls.lang.get('blog.join.leave') : ls.lang.get('blog.join.join') ).toggleClass('button-primary');
				$(this.options.selectors.users_number + '[data-blog-id=' + iIdBlog + ']').text(result.iCountUser);

				ls.hook.run('ls_blog_toggle_join_after', [iIdBlog, result], oToggle);
			}

			oToggle.removeClass(ls.options.classes.states.loading);
		}.bind(this));
	};

	/**
	 * Отображение информации о блоге
	 */
	this.loadInfo = function(iBlogId) {
		if ( ! this.elements.info.length ) return;

		var url = this.options.routers.info,
			params = { idBlog: iBlogId };

		this.elements.info.empty().addClass(ls.options.classes.states.loading);

		ls.hook.marker('loadInfoBefore');

		ls.ajax.load(url, params, function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				this.elements.info.removeClass(ls.options.classes.states.loading).html(result.sText);

				ls.hook.run('ls_blog_load_info_after', [iBlogId, result], this.elements.info);
			}
		}.bind(this));
	};

	/**
	 * Отображение информации о типе блога
	 */
	this.loadInfoType = function(type) {
		this.elements.blog_add_type_note.text(ls.lang.get('blog.add.fields.type.note_' + type));
	};

	/**
	 * Поиск блогов
	 */
	this.searchBlogs = function(sFormSelector) {
		var url = ls.blog.options.routers.search,
			oInputSearch = $(sFormSelector).find('input'),
			oOriginalContainer = $('#blogs-list-original'),
			oSearchContainer = $('#blogs-list-search');

		oInputSearch.addClass(ls.options.classes.states.loading);

		ls.hook.marker('searchBlogsBefore');

		ls.ajax.submit(url, sFormSelector, function(result) {
			oInputSearch.removeClass(ls.options.classes.states.loading);

			if (result.bStateError) {
				oSearchContainer.hide();
				oOriginalContainer.show();
			} else {
				oOriginalContainer.hide();
				oSearchContainer.html(result.sText).show();

				ls.hook.run('ls_blog_search_blogs_after', [sFormSelector, result]);
			}
		});
	};

	/**
	 * Подгружает блоги из категории
	 * 
	 * @param {String} id ID категории
	 */
	this.loadBlogsByCategory = function(iId) {
		var url     = this.options.routers.categories,
			params  = { id: iId };

		this.elements.nav.blogs.empty().prop('disabled', true),
		this.elements.nav.submit.prop('disabled', true).addClass(ls.options.classes.states.loading);

		ls.hook.marker('loadBlogsByCategoryBefore');

		if (iId !== '0') {
			ls.ajax.load(url, params, function(result) {
				if (result.bStateError) {
					this.elements.nav.blogs.append('<option>' + result.sMsg + '</option>');
				} else {
					$($.map(result.aBlogs, function(value, index) {
						return '<option value="' + value.id + '" data-url="' + value.url_full + '">' + value.title + '</option>';
					}).join('')).appendTo(this.elements.nav.blogs);

					this.elements.nav.blogs.prop('disabled', false);
					this.elements.nav.submit.prop('disabled', false).removeClass(ls.options.classes.states.loading);

					ls.hook.run('ls_blog_load_blogs_by_category_after', [iId, result]);
				}

				this.elements.nav.submit.removeClass(ls.options.classes.states.loading);
			}.bind(this));
		} else {
			this.elements.nav.submit.removeClass(ls.options.classes.states.loading);
			this.elements.nav.blogs.html('<option>' + ls.lang.get('blog.blog') + '</option>');
		}
	};

	/**
	 * Переход на страницу выбранного блога
	 */
	this.navigatorGoSelectBlog = function() {
		window.location.href = this.elements.nav.blogs.find('option:selected').data('url') || '';
	};

	/**
	 * Приглашения
	 */
	this.invite = function(_this) {
		/**
		 * Отправляет приглашение вступить в блог
		 */
		this.add = function(iBlogId, sUsers) {
			if( ! sUsers ) return false;

			var sUrl = _this.options.routers.invite.add,
				oParams = { users: sUsers, idBlog: iBlogId };

			_this.elements.invite.form.submit.prop('disabled', true).addClass(ls.options.classes.states.loading);
			_this.elements.invite.form.users.autocomplete('disable');

			ls.hook.marker('addInviteBefore');

			ls.ajax.load(sUrl, oParams, function(result) {
				_this.elements.invite.form.submit.prop('disabled', false).removeClass(ls.options.classes.states.loading);

				if (result.bStateError) {
					ls.msg.error(null, result.sMsg);
				} else {
					_this.elements.invite.form.users.val('');

					$($.map(result.aUsers, function(value, index) {
						if (value.bStateError) {
							ls.msg.error(null, value.sMsg);
						} else {
							ls.msg.notice(null, value.sMsg);
							_this.elements.invite.container.show();

							var oItem = _this.options.html.invite_item(iBlogId, value);

							ls.hook.run('ls_blog_add_invite_user_after', [iBlogId, value], oItem);

							return oItem;
						}
					}).join('')).appendTo(_this.elements.invite.user_list);

					ls.hook.run('ls_blog_add_invite_after', [iBlogId, sUsers, result]);
				}
			});

			return false;
		};

		/**
		 * Удаляет приглашение в блог
		 */
		this.remove = function(iUserId, iBlogId) {
			var sUrl = _this.options.routers.invite.remove,
				oParams = { idUser: iUserId, idBlog: iBlogId };

			ls.hook.marker('removeInviteBefore');

			ls.ajax.load(sUrl, oParams, function(result) {
				if (result.bStateError) {
					ls.msg.error(null, result.sMsg);
				} else {
					ls.msg.notice(null, result.sMsg);

					$(this.options.selectors.invite.user + '[data-user-id=' + iUserId + ']').fadeOut('slow', function() {
						$(this).remove();
						if ($(_this.options.selectors.invite.user).length === 0) _this.elements.invite.container.hide();

						ls.hook.run('ls_blog_remove_invite_after', [iUserId, iBlogId, result]);
					});
				}
			}.bind(_this));

			return false;
		};

		/**
		 * Повторно отправляет приглашение
		 */
		this.repeat = function(iUserId,iBlogId) {
			var sUrl = _this.options.routers.invite.repeat,
				oParams = { idUser: iUserId, idBlog: iBlogId };

			ls.hook.marker('repeatInviteBefore');

			ls.ajax.load(sUrl, oParams, function(result) {
				if (result.bStateError) {
					ls.msg.error(null, result.sMsg);
				} else {
					ls.msg.notice(null, result.sMsg);

					ls.hook.run('ls_blog_repeat_invite_after', [iUserId, iBlogId, result]);
				}
			});

			return false;
		};

		return this;
	}.call({}, this);

	return this;
}).call(ls.blog || {},jQuery);