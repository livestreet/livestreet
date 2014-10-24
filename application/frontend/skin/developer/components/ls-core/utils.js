/**
 * Вспомогательные функции
 *
 * @module utils
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

ls.utils = (function ($) {
	/**
	 * Переводит первый символ в верхний регистр
	 */
	this.ucfirst = function(str) {
		var f = str.charAt(0).toUpperCase();
		return f + str.substr(1, str.length-1);
	};

	/**
	 * Выделяет все chekbox с определенным css классом
	 */
	this.checkAll = function(cssclass, checkbox, invert) {
		$('.'+cssclass).each(function(index, item){
			if (invert) {
				$(item).attr('checked', !$(item).attr("checked"));
			} else {
				$(item).attr('checked', $(checkbox).attr("checked"));
			}
		});
	};

	/**
	 * Предпросмотр
	 */
	this.textPreview = function(mTextSelector, mPreviewSelector, bSave) {
		var sText   = WYSIWYG ? tinyMCE.activeEditor.getContent() : (typeof mTextSelector === 'string' ? $(mTextSelector) : mTextSelector).val(),
			sUrl    = aRouter['ajax'] + 'preview/text/',
			oParams = { text: sText, save: bSave };

		ls.hook.marker('textPreviewAjaxBefore');

		ls.ajax.load(sUrl, oParams, function(result) {
			if (result.bStateError) {
				ls.msg.error(result.sMsgTitle || 'Error', result.sMsg || 'Please try again later');
			} else {
				var oPreview = typeof mTextSelector === 'string' ? $(mPreviewSelector || '#text_preview') : mPreviewSelector;

				ls.hook.marker('textPreviewDisplayBefore');

				if (oPreview.length) {
					oPreview.html(result.sText);

					ls.hook.marker('textPreviewDisplayAfter');
				}
			}
		});
	};

	/**
	 * Возвращает выделенный текст на странице
	 */
	this.getSelectedText = function(){
		var text = '';
		if(window.getSelection){
			text = window.getSelection().toString();
		} else if(window.document.selection){
			var sel = window.document.selection.createRange();
			text = sel.text || sel;
			if(text.toString) {
				text = text.toString();
			} else {
				text = '';
			}
		}
		return text;
	};

	/**
	 * Получает значения атрибутов data с заданным префиксом
	 */
	this.getDataOptions = function (element, prefix) {
		var prefix = prefix || 'option',
			resultOptions = {},
			dataOptions = typeof element === 'string' ? $(element).data() : element.data();

		for (var option in dataOptions) {
			// Remove 'option' prefix
			if (option.substring(0, prefix.length) == prefix) {
				var str = option.substring(prefix.length);
				resultOptions[str.charAt(0).toLowerCase() + str.substring(1)] = dataOptions[option];
			}
		}

		return resultOptions;
	};

	/**
	 * Удаляет классы с заданным префиксом
	 */
	this.removeClassByPrefix = function (element, prefix) {
		element[0].className = $.trim( ( element[0].className + ' ' ).replace(new RegExp('\\b' + prefix + '.*?\\s', 'g'), '') );
	};

	/**
	 * Блокирует/разблокировывает форму
	 */
	this.formLockAccessor = function(sName) {
		return function (oForm) {
			var oElements = oForm.find('input, select, textarea, button').filter(sName == 'lock' ? ':not(:disabled)' : '.js-ls-form-disabled');

			oElements.each(function (iIndex, oInput) {
				$(this).prop('disabled', sName == 'lock' ? true : false)[sName == 'lock' ? 'addClass' : 'removeClass']('js-ls-form-disabled');
			});
		}
	};

	/**
	 * Блокирует форму
	 */
	this.formLock = function(oForm) {
		this.formLockAccessor('lock').apply(this, arguments);
	};

	/**
	 * Разблокировывает форму
	 */
	this.formUnlock = function(oForm) {
		this.formLockAccessor('unlock').apply(this, arguments);
	};

	/**
	 * Возвращает форматированное оставшееся время
	 */
	this.timeRemaining = function(seconds) {
		days = parseInt(seconds / 86400);
		seconds = seconds % 86400;

		hours = parseInt(seconds / 3600);
		seconds = seconds % 3600;

		minutes = parseInt(seconds / 60);
		seconds = parseInt(seconds % 60);

		if (days>0) {
			return days+', '+hours+':'+minutes+':'+seconds;
		}
		if (hours>0) {
			return hours+':'+minutes+':'+seconds;
		}
		if (minutes>0) {
			return minutes+':'+seconds;
		}
		return seconds;
	};

	/**
	 * Экранирует HTML символы в тексте
	 *
	 * @param text
	 * @return {String}
	 */
	this.escapeHtml = function(text) {
		return text
			.replace(/&/g, "&amp;")
			.replace(/</g, "&lt;")
			.replace(/>/g, "&gt;")
			.replace(/"/g, "&quot;")
			.replace(/'/g, "&#039;");
	};

	return this;
}).call(ls.utils || {},jQuery);