<?php

return array(
	'default' => array(
		// Разрешённые теги
		'cfgAllowTags' => array(
			// вызов метода с параметрами
			array(
				array('ls','cut','a', 'img', 'i', 'b', 'u', 's', 'video', 'em',  'strong', 'nobr', 'li', 'ol', 'ul', 'sup', 'abbr', 'sub', 'acronym', 'h4', 'h5', 'h6', 'br', 'hr', 'pre', 'code', 'object', 'param', 'embed', 'blockquote', 'iframe','table','th','tr','td'),
			),			
		),
		// Коротие теги типа
		'cfgSetTagShort' => array(
			array(
				array('br','img', 'hr', 'cut','ls')
			),
		),
		// Преформатированные теги
		'cfgSetTagPreformatted' => array(
			array(
				array('pre','code','video')
			),
		),
		// Разрешённые параметры тегов
		'cfgAllowTagParams' => array(
			// вызов метода
			array(
				'img',
				array('src', 'alt' => '#text', 'title', 'align' => array('right', 'left', 'center', 'middle'), 'width' => '#int', 'height' => '#int', 'hspace' => '#int', 'vspace' => '#int', 'class'=> array('image-center'))
			),
			// следующий вызов метода
			array(
				'a',
				array('title', 'href', 'rel' => '#text', 'name' => '#text', 'target' => array('_blank'))
			),
			// и т.д.
			array(
				'cut',
				array('name')
			),
			array(
				'object',
				array('width' => '#int', 'height' => '#int', 'data' => array('#domain'=>array('youtube.com','rutube.ru','vimeo.com')), 'type' => '#text')
			),
			array(
				'param',
				array('name' => '#text', 'value' => '#text')
			),
			array(
				'embed',
				array('src' => array('#domain'=>array('youtube.com','rutube.ru','vimeo.com')), 'type' => '#text','allowscriptaccess' => '#text', 'allowfullscreen' => '#text','width' => '#int', 'height' => '#int', 'flashvars'=> '#text', 'wmode'=> '#text')
			),
			array(
				'acronym',
				array('title')
			),
			array(
				'abbr',
				array('title')
			),
			array(
				'iframe',
				array('width' => '#int', 'height' => '#int', 'src' => array('#domain'=>array('youtube.com','rutube.ru','vimeo.com')))
			),
			array(
				'ls',
				array('user'=>'#text')
			),
			array(
				'td',
				array('colspan'=>'#int','rowspan'=>'#int','align'=>array('right', 'left', 'center', 'justify'),'height'=>'#int','width'=>'#int')
			),
			array(
				'table',
				array('border'=>'#int','cellpadding'=>'#int','cellspacing'=>'#int','align'=>array('right', 'left', 'center'),'height'=>'#int','width'=>'#int')
			),
		),
		// Параметры тегов являющиеся обязательными
		'cfgSetTagParamsRequired' => array(
			array(
				'img',
				'src'
			),			
		),
		// Теги которые необходимо вырезать из текста вместе с контентом
		'cfgSetTagCutWithContent' => array(
			array(
				array('script',  'style')
			),
		),
		// Вложенные теги
		'cfgSetTagChilds' => array(
			array(
				'ul',
				array('li'),
				false,
				true
			),
			array(
				'ol',
				array('li'),
				false,
				true
			),
			array(
				'object',
				'param',
				false,
				true
			),
			array(
				'object',
				'embed',
				false,
				false
			),
			array(
				'table',
				array('tr'),
				false,
				true
			),
			array(
				'tr',
				array('td','th'),
				false,
				true
			),
		),
		// Если нужно оставлять пустые не короткие теги
		'cfgSetTagIsEmpty' => array(
			array(
				array('param','embed','a','iframe')
			),
		),
		// Не нужна авто-расстановка <br>
		'cfgSetTagNoAutoBr' => array(
			array(
				array('ul','ol','object','table','tr')
			)
		),
		// Теги с обязательными параметрами
		'cfgSetTagParamDefault' => array(
			array(
				'embed',
				'wmode',
				'opaque',
				true
			),
		),
		// Отключение авто-добавления <br>
		'cfgSetAutoBrMode' => array(
			array(
				true
			)
		),
		// Автозамена
		'cfgSetAutoReplace' => array(
			array(
				array('+/-', '(c)', '(с)', '(r)', '(C)', '(С)', '(R)'),
				array('±', '©', '©', '®', '©', '©', '®')
			)
		),
		'cfgSetTagNoTypography' => array(			
			array(
				array('code','video','object')
			),
		),
		// Теги, после которых необходимо пропускать одну пробельную строку
		'cfgSetTagBlockType' => array(
			array(
				array('h4','h5','h6','ol','ul','blockquote','pre')
			)
		),
		'cfgSetTagCallbackFull' => array(
			array(
				'ls',
				array('_this_','CallbackTagLs'),
			),
		),
	),
	
	// настройки для обработки текста в результатах поиска
	'search' => array(
		// Разрешённые теги
		'cfgAllowTags' => array(
			// вызов метода с параметрами
			array(
				array('span'),
			),			
		),
		// Разрешённые параметры тегов
		'cfgAllowTagParams' => array(
			array(
				'span',
				array('class' => '#text')
			),			
		),
	),
);