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
mySettings = {	
	onShiftEnter:  	{keepDefault:false, replaceWith:'<br />\n'},
	onCtrlEnter:  	{keepDefault:false, openWith:'\n<p>', closeWith:'</p>'},
	onTab:    		{keepDefault:false, replaceWith:'    '},
	previewParserPath: aRouter['ajax']+'preview/text/',
	previewParserVar: 'text',
	markupSet:  [ 	
		{name:'H4', className:'editor-h4', openWith:'<h4>', closeWith:'</h4>' },
		{name:'H5', className:'editor-h5', openWith:'<h5>', closeWith:'</h5>' },
		{name:'H6', className:'editor-h6', openWith:'<h6>', closeWith:'</h6>' },
		{separator:'---------------' },
		{name:'Жирный', className:'editor-bold', key:'B', openWith:'(!(<strong>|!|<b>)!)', closeWith:'(!(</strong>|!|</b>)!)' },
		{name:'Наклонный', className:'editor-italic', key:'I', openWith:'(!(<em>|!|<i>)!)', closeWith:'(!(</em>|!|</i>)!)'  },
		{name:'Перечеркнутый', className:'editor-stroke', key:'S', openWith:'<del>', closeWith:'</del>' },
		{name:'Подчеркнутый', className:'editor-underline', key:'U', openWith:'<u>', closeWith:'</u>' },
		{name:'Цитата', className:'editor-quote', key:'Q', openWith:'<blockquote>', closeWith:'</blockquote>' },
		{name:'Код', className:'editor-code', openWith:'<code>', closeWith:'</code>' },
		{separator:'---------------' },
        {name:'Список', className:'editor-ul', openWith:'<ul>\n', closeWith:'</ul>\n' },
        {name:'Нумерованный список', className:'editor-ol', openWith:'<ol>\n', closeWith:'</ol>\n' },
        {name:'Пункт списка', className:'editor-li', openWith:'<li>', closeWith:'</li>' },
		{separator:'---------------' },
		{name:'Изображение', className:'editor-picture', key:'P', beforeInsert: function(h) { $('#form_upload_img').jqmShow(); } },
		{name:'Видео', className:'editor-video', replaceWith:'<video>[![Ссылка на видео:!:http://]!]</video>' },
		{name:'Ссылка', className:'editor-link', key:'L', openWith:'<a href="[![Link:!:http://]!]"(!( title="[![Title]!]")!)>', closeWith:'</a>', placeHolder:'Your text to link...' },
		{separator:'---------------' },
		{name:'Очистить от тегов', className:'editor-clean', replaceWith: function(markitup) { return markitup.selection.replace(/<(.*?)>/g, "") } },		
		{name:'Предпросмотр', className:'editor-preview',  call:'preview'},
		{name:'Вставить разделитель <cut>', className:'editor-cut', openWith:'<cut>', className:'cut'}
	]
}