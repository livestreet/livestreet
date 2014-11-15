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
			categories: aRouter['ajax'] + 'blogs/get-by-category/',
		},

		// Селекторы
		selectors: {
			addBlogSelectType: '.js-blog-add-type',
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
			blog_add_type_note: $(this.options.selectors.blog_add_type_note),
		};

		// Подгрузка информации о выбранном типе блога при создании блога
		$(this.options.selectors.addBlogSelectType).on('change', function (e) {
			_this.loadInfoType($(this).val());
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