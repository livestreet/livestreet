// ----------------------------------------------------------------------------
// markItUp!
// ----------------------------------------------------------------------------
// Copyright (C) 2008 Jay Salvat
// http://markitup.jaysalvat.com/
// ----------------------------------------------------------------------------
// Html tags
// http://en.wikipedia.org/wiki/html
// ----------------------------------------------------------------------------
// Basic set. Feel free to add more tags
// ----------------------------------------------------------------------------

function getMarkitupSettings() {
	return {
		onShiftEnter:  	{keepDefault:false, replaceWith:'<br />\n'},
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
			{name: ls.lang.get('panel_code'), className:'editor-code', openWith:'<code>', closeWith:'</code>' },
			{separator:'---------------' },
	        {name: ls.lang.get('panel_list'), className:'editor-ul', openWith:'    <li>', closeWith:'</li>', multiline: true, openBlockWith:'<ul>\n', closeBlockWith:'\n</ul>' },
	        {name: ls.lang.get('panel_list'), className:'editor-ol', openWith:'    <li>', closeWith:'</li>', multiline: true, openBlockWith:'<ol>\n', closeBlockWith:'\n</ol>' },
			{separator:'---------------' },
			{name: ls.lang.get('panel_image'), className:'editor-picture', key:'P', beforeInsert: function(h) { $('#form_upload_img').jqmShow(); } },
			{name: ls.lang.get('panel_image'), className:'editor-image', replaceWith:'<img src="[!['+ls.lang.get('panel_image_promt')+':!:http://]!]" />' },
			{name: ls.lang.get('panel_video'), className:'editor-video', replaceWith:'<video>[!['+ls.lang.get('panel_video_promt')+':!:http://]!]</video>' },
			{name: ls.lang.get('panel_url'), className:'editor-link', key:'L', openWith:'<a href="[!['+ls.lang.get('panel_url_promt')+':!:http://]!]"(!( title="[![Title]!]")!)>', closeWith:'</a>', placeHolder:'Your text to link...' },
			{name: ls.lang.get('panel_user'), className:'editor-user', replaceWith:'<ls user="[!['+ls.lang.get('panel_user_promt')+']!]" />' },
			{separator:'---------------' },
			{name: ls.lang.get('panel_clear_tags'), className:'editor-clean', replaceWith: function(markitup) { return markitup.selection.replace(/<(.*?)>/g, "") } },
			{name: ls.lang.get('panel_cut'), className:'editor-cut', replaceWith: function(markitup) { if (markitup.selection) return '<cut name="'+markitup.selection+'">'; else return '<cut>' }}
		]
	}
}

function getMarkitupCommentSettings() {
	return {	
		onShiftEnter:  	{keepDefault:false, replaceWith:'<br />\n'},
		onTab:    		{keepDefault:false, replaceWith:'    '},
		markupSet:  [ 
			{name: ls.lang.get('panel_b'), className:'editor-bold', key:'B', openWith:'(!(<strong>|!|<b>)!)', closeWith:'(!(</strong>|!|</b>)!)' },
			{name: ls.lang.get('panel_i'), className:'editor-italic', key:'I', openWith:'(!(<em>|!|<i>)!)', closeWith:'(!(</em>|!|</i>)!)'  },
			{name: ls.lang.get('panel_s'), className:'editor-stroke', key:'S', openWith:'<s>', closeWith:'</s>' },
			{name: ls.lang.get('panel_u'), className:'editor-underline', key:'U', openWith:'<u>', closeWith:'</u>' },
			{separator:'---------------' },
			{name: ls.lang.get('panel_quote'), className:'editor-quote', key:'Q', replaceWith: function(m) { if (m.selectionOuter) return '<blockquote>'+m.selectionOuter+'</blockquote>'; else if (m.selection) return '<blockquote>'+m.selection+'</blockquote>'; else return '<blockquote></blockquote>' } },
			{name: ls.lang.get('panel_code'), className:'editor-code', openWith:'<code>', closeWith:'</code>' },
			{name: ls.lang.get('panel_image'), className:'editor-picture', key:'P', beforeInsert: function(h) { $('#form_upload_img').jqmShow(); } },
			{name: ls.lang.get('panel_image'), className:'editor-image', replaceWith:'<img src="[!['+ls.lang.get('panel_image_promt')+':!:http://]!]" />' },
			{name: ls.lang.get('panel_url'), className:'editor-link', key:'L', openWith:'<a href="[!['+ls.lang.get('panel_url_promt')+':!:http://]!]"(!( title="[![Title]!]")!)>', closeWith:'</a>', placeHolder:'Your text to link...' },
			{name: ls.lang.get('panel_user'), className:'editor-user', replaceWith:'<ls user="[!['+ls.lang.get('panel_user_promt')+']!]" />' },
			{separator:'---------------' },
			{name: ls.lang.get('panel_clear_tags'), className:'editor-clean', replaceWith: function(markitup) { return markitup.selection.replace(/<(.*?)>/g, "") } }
		]
	}
}
