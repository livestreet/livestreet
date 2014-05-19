/**
 * Markup редактор
 *
 * @module ls/editor
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};
ls.editor = ls.editor || {};

ls.editor.markup = (function($) {
	"use strict";

	/**
	 * Дефолтные опции
	 */
	var defaults = {
		sets: {
			default: {
				onShiftEnter:  	{keepDefault:false, replaceWith:'<br />\n'},
				onCtrlEnter:  	{keepDefault:false, openWith:'\n<p>', closeWith:'</p>'},
				onTab:    		{keepDefault:false, replaceWith:'    '},
				markupSet:  [
					{name:'H4', className:'editor-h4', openWith:'<h4>', closeWith:'</h4>' },
					{name:'H5', className:'editor-h5', openWith:'<h5>', closeWith:'</h5>' },
					{name:'H6', className:'editor-h6', openWith:'<h6>', closeWith:'</h6>' },
					{separator:'---------------' },
					{name: ls.lang.get('panel_b'), className:'editor-bold', key:'B', openWith:'(!(<strong>|!|<b>)!)', closeWith:'(!(</strong>|!|</b>)!)' },
					{name: ls.lang.get('panel_i'), className:'editor-italic', key:'I', openWith:'(!(<em>|!|<i>)!)', closeWith:'(!(</em>|!|</i>)!)'  },
					{name: ls.lang.get('panel_s'), className:'editor-stroke', key:'S', openWith:'<s>', closeWith:'</s>' },
					{name: ls.lang.get('panel_u'), className:'editor-underline', key:'U', openWith:'<u>', closeWith:'</u>' },
					{name: ls.lang.get('panel_quote'), className:'editor-quote', key:'Q', replaceWith: function(m) { if (m.selectionOuter) return '<blockquote>'+m.selectionOuter+'</blockquote>'; else if (m.selection) return '<blockquote>'+m.selection+'</blockquote>'; else return '<blockquote></blockquote>' } },
					{name: ls.lang.get('panel_code'), className:'editor-code', openWith:'<(!(code|!|codeline)!)>', closeWith:'</(!(code|!|codeline)!)>' },
					{separator:'---------------' },
					{name: ls.lang.get('panel_list'), className:'editor-ul', openWith:'    <li>', closeWith:'</li>', multiline: true, openBlockWith:'<ul>\n', closeBlockWith:'\n</ul>' },
					{name: ls.lang.get('panel_list'), className:'editor-ol', openWith:'    <li>', closeWith:'</li>', multiline: true, openBlockWith:'<ol>\n', closeBlockWith:'\n</ol>' },
					{name: ls.lang.get('panel_list_li'), className:'editor-li', openWith:'<li>', closeWith:'</li>' },
					{separator:'---------------' },
					{name: ls.lang.get('panel_image'), className:'editor-picture', key:'P', beforeInsert: function(h) { jQuery('#modal-image-upload').modal('show'); } },
					{name: ls.lang.get('panel_video'), className:'editor-video', replaceWith:'<video>[!['+ls.lang.get('panel_video_promt')+':!:http://]!]</video>' },
					{name: ls.lang.get('panel_url'), className:'editor-link', key:'L', openWith:'<a href="[!['+ls.lang.get('panel_url_promt')+':!:http://]!]"(!( title="[![Title]!]")!)>', closeWith:'</a>', placeHolder:'Your text to link...' },
					{name: ls.lang.get('panel_user'), className:'editor-user', replaceWith:'<ls user="[!['+ls.lang.get('panel_user_promt')+']!]" />' },
					{separator:'---------------' },
					{name: ls.lang.get('panel_clear_tags'), className:'editor-clean', replaceWith: function(markitup) { return markitup.selection.replace(/<(.*?)>/g, "") } },
					{name: ls.lang.get('panel_cut'), className:'editor-cut', replaceWith: function(markitup) { if (markitup.selection) return '<cut name="'+markitup.selection+'">'; else return '<cut>' }}
				]
			},
			light: {
				onShiftEnter:  	{keepDefault:false, replaceWith:'<br />\n'},
				onTab:    		{keepDefault:false, replaceWith:'    '},
				markupSet:  [
					{name: ls.lang.get('panel_b'), className:'editor-bold', key:'B', openWith:'(!(<strong>|!|<b>)!)', closeWith:'(!(</strong>|!|</b>)!)' },
					{name: ls.lang.get('panel_i'), className:'editor-italic', key:'I', openWith:'(!(<em>|!|<i>)!)', closeWith:'(!(</em>|!|</i>)!)'  },
					{name: ls.lang.get('panel_s'), className:'editor-stroke', key:'S', openWith:'<s>', closeWith:'</s>' },
					{name: ls.lang.get('panel_u'), className:'editor-underline', key:'U', openWith:'<u>', closeWith:'</u>' },
					{separator:'---------------' },
					{name: ls.lang.get('panel_quote'), className:'editor-quote', key:'Q', replaceWith: function(m) { if (m.selectionOuter) return '<blockquote>'+m.selectionOuter+'</blockquote>'; else if (m.selection) return '<blockquote>'+m.selection+'</blockquote>'; else return '<blockquote></blockquote>' } },
					{name: ls.lang.get('panel_code'), className:'editor-code', openWith:'<(!(code|!|codeline)!)>', closeWith:'</(!(code|!|codeline)!)>' },
					{name: ls.lang.get('panel_image'), className:'editor-picture', key:'P', beforeInsert: function(h) { jQuery('#modal-image-upload').modal('show'); } },
					{name: ls.lang.get('panel_url'), className:'editor-link', key:'L', openWith:'<a href="[!['+ls.lang.get('panel_url_promt')+':!:http://]!]"(!( title="[![Title]!]")!)>', closeWith:'</a>', placeHolder:'Your text to link...' },
					{name: ls.lang.get('panel_user'), className:'editor-user', replaceWith:'<ls user="[!['+ls.lang.get('panel_user_promt')+']!]" />' },
					{separator:'---------------' },
					{name: ls.lang.get('panel_clear_tags'), className:'editor-clean', replaceWith: function(markitup) { return markitup.selection.replace(/<(.*?)>/g, "") } }
				]
			}
		}
	};

	/**
	 * Инициализация
	 *
	 */
	this.init = function(element, set) {
		var self = this;

		this.options = $.extend({}, defaults);

		element.markItUp(this.options.sets[set]);

		// Справка по разметке редактора
		$('.js-editor-help-toggle').on('click', function (e) {
			$(this).parent().next().toggle();
			e.preventDefault();
		});

		$('.js-editor-help').each(function () {
			var oEditorHelp = $(this),
				oTargetForm = $('#' + oEditorHelp.data('form-id'));

			oEditorHelp.find('.js-tags-help-link').on('click', function (e) {
				if ($(this).data('insert')) {
					var sTag = $(this).data('insert');
				} else {
					var sTag = $(this).text();
				}

				$.markItUp({
					target: oTargetForm,
					replaceWith: sTag
				});

				e.preventDefault();
			});
		});
	};

	/**
	 * Вставка ссылки загруженного изображения в редактор
	 *
	 * @param  {String} sUrl   Ссылка
	 * @param  {String} sAlign Выравнивание
	 * @param  {String} sTitle Описание
	 */
	this.insertImageUrlToEditor = function(sUrl, sAlign, sTitle) {
		sAlign = sAlign == 'center' ? 'class="image-center"' : 'align="' + sAlign + '"';

		$.markItUp({
			replaceWith: '<img src="' + sUrl + '" title="' + sTitle + '" ' + sAlign + ' />'
		});
	};

	return this;
}).call(ls.editor.markup || {},jQuery);