// ----------------------------------------------------------------------------
// TinyMCE Settings
// ----------------------------------------------------------------------------


function getTinymceCommentSettings() {
	return {	
		mode : 									"textareas",
		theme : 								"advanced",
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
		plugins : 								"lseditor,safari,inlinepopups,media,pagebreak",
		convert_urls : 							false,
		extended_valid_elements : 				"embed[src|type|allowscriptaccess|allowfullscreen|width|height]",
		pagebreak_separator :					"<cut>",
		media_strict : 							false,
		language : 								TINYMCE_LANG,
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

function getTinymceTopicSettings() {
	return {	
		mode : 									"specific_textareas",
		editor_selector : 						"mce-editor",
		theme : 								"advanced",
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
		plugins : 								"lseditor,safari,inlinepopups,media,pagebreak",
		convert_urls : 							false,
		extended_valid_elements : 				"embed[src|type|allowscriptaccess|allowfullscreen|width|height]",
		pagebreak_separator :					"<cut>",
		media_strict : 							false,
		language : 								TINYMCE_LANG,
		inline_styles:							false,
		formats : {
			underline : 	{inline : 'u', exact : true},
			strikethrough : {inline : 's', exact : true}
		}
	}
}
