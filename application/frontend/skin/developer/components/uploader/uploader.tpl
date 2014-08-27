{**
 * Загрузка и управление файлами
 *}

{$component = 'uploader'}

{block 'block_options'}
	{$mods = $smarty.local.mods}
	{$classes = $smarty.local.classes}
	{$attributes = $smarty.local.attributes}
	{$show = $smarty.local.show|default:true}
{/block}

<div class="{$component} {mod name=$component mods=$mods} {$classes}" {$attributes}
	data-params={json var=$smarty.local.targetParams}
	data-type={json var=$smarty.local.targetType}
	data-id={json var=$smarty.local.targetId}
	data-tmp={json var=$smarty.local.targetTmp}>

	{block 'uploader_content'}
		{* Drag & drop зона *}
		{include 'components/field/field.upload-area.tpl'
			classes      = 'js-media-upload-area'
			inputClasses = 'js-media-upload-file'
			inputName    = 'filedata'}

		{* Галерея *}
		<div class="{$component}-wrapper clearfix">
			{* Список файлов *}
			<ul class="{$component}-file-list js-media-upload-gallery-list"></ul>

			{* Информация о выделенном файле *}
			<div class="{$component}-aside">
				{block 'uploader_aside'}
					{include './uploader-info.tpl'}
				{/block}
			</div>
		</div>
	{/block}
</div>