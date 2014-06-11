/**
 * Визуальный редактор
 *
 * @module ls/editor
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};
ls.editor = ls.editor || {};

ls.editor.visual = (function($) {
	"use strict";

	/**
	 * Дефолтные опции
	 */
	var defaults = {
		sets: {
			default: {
				mode : 									"specific_textareas",
				editor_selector : 						"js-editor",
				theme : 								"advanced",
				skin : 								    "livestreet",
				width : 								"100%",
				theme_advanced_toolbar_location : 		"top",
				theme_advanced_toolbar_align : 			"left",
				theme_advanced_buttons1 : 				"lshselect,bold,italic,underline,strikethrough,|,bullist,numlist,|,undo,redo,|,lslink,unlink,lsvideo,lsimage,pagebreak,code",
				theme_advanced_buttons2 : 				"",
				theme_advanced_buttons3 : 				"",
				theme_advanced_statusbar_location : 	"bottom",
				theme_advanced_resizing : 				true,
				theme_advanced_resize_horizontal : 		0,
				theme_advanced_resizing_use_cookie : 	0,
				theme_advanced_path : 					false,
				object_resizing : 						true,
				force_br_newlines :						true,
				forced_root_block : 					'', // Needed for 3.x
				force_p_newlines : 						false,
				plugins : 								"lseditor,safari,inlinepopups,media,pagebreak,autoresize",
				convert_urls : 							false,
				extended_valid_elements : 				"embed[src|type|allowscriptaccess|allowfullscreen|width|height]",
				pagebreak_separator :					"<cut>",
				media_strict : 							false,
				language : 								LANGUAGE,
				inline_styles:							false,
				formats : {
					underline : 	{inline : 'u', exact : true},
					strikethrough : {inline : 's', exact : true}
				}
			},
			light: {
				mode : 									"textareas",
				theme : 								"advanced",
				skin : 								    "livestreet",
				width : 								"100%",
				theme_advanced_toolbar_location : 		"top",
				theme_advanced_toolbar_align : 			"left",
				theme_advanced_buttons1 : 				"bold,italic,underline,strikethrough,lslink,lsquote",
				theme_advanced_buttons2 : 				"",
				theme_advanced_buttons3 : 				"",
				theme_advanced_statusbar_location : 	"bottom",
				theme_advanced_resizing : 				true,
				theme_advanced_resize_horizontal : 		0,
				theme_advanced_resizing_use_cookie : 	0,
				theme_advanced_path : 					false,
				object_resizing : 						true,
				force_br_newlines : 					true,
				forced_root_block : 					'', // Needed for 3.x
				force_p_newlines : 						false,
				plugins : 								"lseditor,safari,inlinepopups,media,pagebreak,autoresize",
				convert_urls : 							false,
				extended_valid_elements : 				"embed[src|type|allowscriptaccess|allowfullscreen|width|height]",
				pagebreak_separator :					"<cut>",
				media_strict : 							false,
				language : 								'ru',
				inline_styles:							false,
				formats : {
					underline : 	{inline : 'u', exact : true},
					strikethrough : {inline : 's', exact : true}
				},
				setup : function(ed) {
					// Display an alert onclick
					ed.onKeyPress.add(function(ed, e) {
						key = e.keyCode || e.which;
						if(e.ctrlKey && (key == 13)) {
							$('#comment-button-submit').click();
							return false;
						}
					});
				}
			}
		}
	};

	/**
	 * Инициализация
	 */
	this.init = function(element, set) {
		this.options = $.extend({}, defaults);

		// Т.к. тини не принимает jquery элементы в качестве селектора
		// пишем небольшой костыль который генерит рандомный класс и используем его как селектор
		var selector = 'tinymce' + Math.floor(Math.random() * 10e10);

		element.addClass(selector);

		this.options.sets[set].editor_selector = selector;
		tinyMCE.init(this.options.sets[set]);
	};

	return this;
}).call(ls.editor.visual || {},jQuery);