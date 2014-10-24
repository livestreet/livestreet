/**
 * Модаль для работы с локализацией
 *
 * @module i18n
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

ls.lang = ls.i18n = (function ($) {
	"use strict";

	/**
	 * Набор текстовок
	 *
	 * @private
	 */
	var _aMsgs = {};

	/**
	 * Правило образования слов во множественном числе
	 * TODO: Вынести в лок-ию или конфиг
	 */
	this.oPluralRules = {
		ru: '(n % 10 == 1 && n % 100 != 11 ? 0 : n % 10 >= 2 && n % 10 <= 4 && (n % 100 < 10 || n % 100 >= 20) ? 1 : 2)',
		ua: '(n % 10 == 1 && n % 100 != 11 ? 0 : n % 10 >= 2 && n % 10 <= 4 && (n % 100 < 10 || n % 100 >= 20) ? 1 : 2)',
		en: '(n != 1)'
	};

	/**
	 * Загрузка текстовок
	 *
	 * @param {Object} msgs Текстовки
	 */
	this.load = function(msgs) {
		$.extend(true, _aMsgs, msgs);
	};

	/**
	 * Получает текстовку
	 *
	 * @param {String} sName    Название текстовки
	 * @param {String} oReplaceStrings Список аргументов для замены
	 */
	this.get = function(sName, oReplaceStrings) {
		if (_aMsgs[sName]) {
			var sValue = _aMsgs[sName];

			if (oReplaceStrings) {
				sValue = this.replace(sValue, oReplaceStrings);
			}

			return sValue;
		}

		return sName;
	};

	/**
	 * Заменят переменные вида %%var%% в текстовках на заданные значения
	 */
	this.replace = function(sString, oParams) {
		jQuery.each(oParams, function(sIndex, sValue) {
			sString = sString.replace( new RegExp('%%' + sIndex + '%%', 'g'), sValue );
		});

		return sString;
	};

	/**
	 * Склонение слов после числительных
	 *
	 * @param  {String} iNumber   Кол-во объектов
	 * @param  {Mixed}  mText     Ключ с текстовкам разделенными символом ';', либо массив
	 * @param  {String} sLanguage Язык, опциональный параметр, по дефолту берется из настроек
	 * @return {String}
	 */
	this.pluralize = function(iNumber, mText, sLanguage) {
		var aWords        = $.isArray(mText) ? mText : this.get(mText).split(';'),
			sLanguage     = sLanguage || LANGUAGE,
			n             = Math.abs(iNumber);

		if (!this.oPluralRules[sLanguage]) {
			var mIndex=0;
		} else {
			var mIndex=eval(this.oPluralRules[sLanguage]);
		}
		// fix type
		mIndex=(typeof mIndex === 'boolean') ? (mIndex ? 1 : 0) : mIndex;
		if (aWords[mIndex]) {
			var sWord=aWords[mIndex];
		} else {
			var sWord=aWords[0] ? aWords[0] : '';
		}
		var sReplacedWord = this.replace( sWord, { count: iNumber } );
		return sWord === sReplacedWord ? iNumber + ' ' + sWord : sReplacedWord;
	};

	return this;
}).call(ls.lang || {}, jQuery);