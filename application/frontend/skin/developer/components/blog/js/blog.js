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
		// Селекторы
		selectors: {
			addBlogSelectType: '.js-blog-add-type',
			blog_add_type_note: '#blog_type_note',
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
			blog_add_type_note: $(this.options.selectors.blog_add_type_note),
		};

		// Подгрузка информации о выбранном типе блога при создании блога
		$(this.options.selectors.addBlogSelectType).on('change', function (e) {
			_this.loadInfoType($(this).val());
		});
	};

	/**
	 * Отображение информации о типе блога
	 */
	this.loadInfoType = function(type) {
		this.elements.blog_add_type_note.text(ls.lang.get('blog.add.fields.type.note_' + type));
	};

	return this;
}).call(ls.blog || {},jQuery);