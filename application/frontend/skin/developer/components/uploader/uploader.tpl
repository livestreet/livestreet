{**
 * Загрузка и управление файлами
 *}

{$component = 'uploader'}

{block 'uploader_options'}
	{$mods = $smarty.local.mods}
	{$classes = $smarty.local.classes}
	{$attributes = $smarty.local.attributes}
	{$show = $smarty.local.show|default:true}
{/block}

<div class="{$component} {cmods name=$component mods=$mods} {$classes}" {cattr list=$attributes}>
	{* @hook Начало основного блока загрузчика *}
	{hook run='uploader_begin'}

	{block 'uploader_content'}
		{* Drag & drop зона *}
		{component 'field' template='upload-area'
			classes      = 'js-uploader-area'
			inputClasses = 'js-uploader-file'
			inputName    = 'filedata'}

		{* @hook Хук после зоны загрузки *}
		{hook run='uploader_area_after'}

		{* Враппер *}
		<div class="{$component}-wrapper clearfix">
			{* Сайдбар *}
			<div class="{$component}-aside js-uploader-aside is-empty">
				{* Блок отображаемый когда нет активного файла *}
				{component 'alert'
					mods    = 'empty'
					text    = {lang name='uploader.info.empty'}
					classes = "{$component}-aside-empty js-{$component}-aside-empty"}

				{* Блоки *}
				<div class="{$component}-aside-blocks js-uploader-blocks">
					{block 'uploader_aside'}
						{include './uploader-block.info.tpl'}
					{/block}
				</div>
			</div>

			{* Основное содержимое *}
			<div class="{$component}-content js-uploader-content">
				{* @hook Начало контента *}
				{hook run='uploader_content_begin'}

				{* Список файлов *}
				<ul class="{$component}-file-list js-uploader-list"></ul>

				{* @hook Конец контента *}
				{hook run='uploader_content_end'}
			</div>
		</div>
	{/block}

	{* @hook Конец основного блока загрузчика *}
	{hook run='uploader_end'}
</div>