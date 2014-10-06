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
		},

		// Селекторы
		selectors: {
			addBlogSelectType: '.js-blog-add-type',
			toggle_join: '.js-blog-join',
			users_number: '.js-blog-users-number',
			info: '.js-blog-info',
			blog_add_type_note: '#blog_type_note',
			nav: {
				categories: '.js-blog-nav-categories',
				blogs:      '.js-blog-nav-blogs',
				submit:     '.js-blog-nav-submit'
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
			nav: {
				categories: $(this.options.selectors.nav.categories),
				blogs:      $(this.options.selectors.nav.blogs),
				submit:     $(this.options.selectors.nav.submit)
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
		$(document).on('click', this.options.selectors.toggle_join, function (e) {
			_this.toggleJoin($(this), $(this).data('blog-id'));
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

				oToggle.empty().text( result.bState ? ls.lang.get('blog.join.leave') : ls.lang.get('blog.join.join') ).toggleClass('button--primary');
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
			this.elements.nav.blogs.html('<option>' + ls.lang.get('blog.blocks.navigator.blog') + '</option>');
		}
	};

	/**
	 * Переход на страницу выбранного блога
	 */
	this.navigatorGoSelectBlog = function() {
		window.location.href = this.elements.nav.blogs.find('option:selected').data('url') || '';
	};

	return this;
}).call(ls.blog || {},jQuery);