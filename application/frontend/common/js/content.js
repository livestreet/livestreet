/**
 * Контент
 * 
 * @module ls/content
 * 
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

ls.content = (function ($) {
	"use strict";

	/**
	 * Дефолтные опции
	 */
	var defaults = {
		// Роутеры
		routers: {
			add: aRouter['content'] + 'ajax/add/',
			edit: aRouter['content'] + 'ajax/edit/'
		}
	};

	/**
	 * Инициализация
	 *
	 * @param  {Object} options Опции
	 */
	this.init = function(options) {
		this.options = $.extend({}, defaults, options);

		$('#submit-add-topic-publish').on('click',function(){
			ls.content.save('add','form-topic-add',{ 'submit_topic_publish': 1 });
		});

		$('#submit-add-topic-save').on('click',function(){
			ls.content.save('add','form-topic-add',{ 'submit_topic_save': 1 });
		});

		$('#submit-edit-topic-publish').on('click',function(){
			ls.content.save('edit','form-topic-add',{ 'submit_topic_publish': 1 });
		});

		$('#submit-edit-topic-save').on('click',function(){
			ls.content.save('edit','form-topic-add',{ 'submit_topic_save': 1 });
		});
	};

	/**
	 * Создание контента
	 *
	 * @param  {String} sFormId ID формы
	 */
	this.save = function(submitType,sFormId,params) {
		var oForm = $('#' + sFormId);
		ls.ajax.submit(this.options.routers[submitType], oForm, function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				if (result.sUrlRedirect) {
					window.location.href=result.sUrlRedirect;
				}
			}
		},{ params: params });
		return false;
	};

	return this;
}).call(ls.content || {}, jQuery);