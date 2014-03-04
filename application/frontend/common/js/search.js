/**
 * Поиск
 *
 * @module ls/search
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

ls.search = (function ($) {
	"use strict";

	/**
	 * Дефолтные опции
	 *
	 * @private
	 */
	var _defaults = {
		// Типы
		type: {
			blogs: {
				url: aRouter['blogs'] + 'ajax-search/',
				params: {}
			},
			users: {
				url: aRouter['people'] + 'ajax-search/',
				params: {}
			}
		},

		// Селекторы
		selectors: {
			option: '.js-search-ajax-option',
			alphabet: '.js-search-alphabet',
			alphabet_item: '.js-search-alphabet-item'
		}
	};

	/**
	 * Инициализация
	 *
	 * @param {Object} options Опции
	 * TODO: Оптимизировать иниц-ию фильтров
	 */
	this.init = function(options) {
		var _this = this;

		this.options = $.extend({}, _defaults, options);

		var oSearchOptions = $(this.options.selectors.option);


		// Search by prefix
		var oSearchAlhpabet = $(this.options.selectors.alphabet).eq(0),
			oSearchAlhpabetType = oSearchAlhpabet.data('type'),
			oSearchAlhpabetItems = oSearchAlhpabet.find(this.options.selectors.alphabet_item),
			oSearchText = $('.js-search-text-main[data-type=' + oSearchAlhpabetType + ']');

		oSearchAlhpabetItems.on('click', function (e) {
			var oElement = $(this),
				sLetter = oElement.data('letter');

			oSearchAlhpabetItems.removeClass(ls.options.classes.states.active);
			oElement.addClass(ls.options.classes.states.active);

			oSearchText.val((sLetter ? '%' : '') + sLetter).keyup();

			e.preventDefault();
		});


		// Text
		oSearchOptions.filter('input[type=text]').each(function () {
			var oElement = $(this),
				sType = oElement.data('type');

			oElement.on('keyup', function () {
				_this.setParam(sType, 'sText', oElement.val());

				ls.timer.run(_this, _this.search, 'search_type_' + sType, [sType], 300);
			});
		});


		// Checkbox, Radio, Select
		// TODO: multiselect
		oSearchOptions.filter('input[type=checkbox], input[type=radio], select').on('change', function (e) {
			var oElement = $(this),
				sParamName = oElement.attr('name'),
				sType = oElement.data('search-type'),
				sInputType = oElement.attr('type') || 'select',
				mValue = null;

			if (sInputType == 'checkbox') {
				mValue = oElement.is(':checked') ? 1 : 0;
			} else if (sInputType == 'radio' || sInputType == 'select') {
				mValue = oElement.val();
			}

			_this.setParam(sType, sParamName, mValue);
			_this.search(sType);
		});


		// Lists
		oSearchOptions.filter('li').on('click', function (e) {
			var oElement = $(this),
				sParamName = oElement.data('name'),
				mValue = oElement.data('value'),
				sType = oElement.data('search-type');

			oElement.closest('ul').find('li').not(oElement).removeClass(ls.options.classes.states.active);
			oElement.addClass(ls.options.classes.states.active);

			_this.setParam(sType, sParamName, mValue);
			_this.search(sType);

			e.preventDefault();
		});


		// Sort
		var aSortItems = $('.js-search-sort-menu li');

		aSortItems.on('click', function (e) {
			var oElement = $(this),
				sParamName = oElement.data('name'),
				mValue = oElement.data('value'),
				sOrder = oElement.attr('data-order'),
				sType = oElement.data('search-type');

			aSortItems.not(oElement).removeClass(ls.options.classes.states.active).attr('data-order', 'asc');

			oElement.attr('data-order', sOrder == 'asc' ? 'desc' : 'asc');

			_this.setParam(sType, sParamName, mValue);
			_this.setParam(sType, 'order', sOrder);
			_this.search(sType);

			e.preventDefault();
		});
	};

	/**
	 * Поиск
	 *
	 * @param  {String} sType   Тип
	 * @param  {Object} oParams Параметры передаваемые при аякс запросе
	 */
	this.search = function (sType) {
		var sUrl    = this.options.type[sType].url,
			oParams = this.options.type[sType].params;

		ls.hook.marker('ls_search');

		ls.ajax.load(sUrl, $.extend({}, oParams), function (oResponse) {
			this.getContainer(sType).html($.trim(oResponse.sText));

			ls.hook.run('ls_search_after', [sType, oResponse]);
		}.bind(this));
	};

	/**
	 * Получает контейнер в который будут выводится результаты поиска
	 *
	 * @param  {String} sType Тип
	 */
	this.getContainer = function (sType) {
		return this.options.type[sType].container || ( this.options.type[sType].container = $('.js-search-ajax-container[data-type=' + sType + ']') );
	};

	/**
	 * Устанавливает параметр аякс запроса
	 *
	 * @param  {String} sType  Тип
	 * @param  {String} sName  Имя параметра
	 * @param  {Mixed}  mValue Значение параметра
	 */
	this.setParam = function (sType, sName, mValue) {
		this.options.type[sType].params[sName] = mValue;
		this.updateUrl(sType);
	};

	/**
	 * Получает параметр аякс запроса
	 *
	 * @param  {String} sType  Тип
	 * @param  {String} sName  Имя параметра
	 */
	this.getParam = function (sType, sName) {
		return this.options.type[sType].params[sName];
	};

	/**
	 * Обновляет ссылку на основе параметров
	 *
	 * @param  {String} sType  Тип
	 */
	this.updateUrl = function (sType) {
		// window.history.pushState({}, 'Search', window.location.origin + window.location.pathname + '?' + $.param(this.options.type[sType].params));
	};

	return this;
}).call(ls.search || {}, jQuery);