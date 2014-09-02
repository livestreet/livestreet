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

<div class="{$component} {mod name=$component mods=$mods} {$classes}" {$attributes}
	data-params={json var=$smarty.local.targetParams}
	data-type={json var=$smarty.local.targetType}
	data-id={json var=$smarty.local.targetId}
	data-tmp={json var=$smarty.local.targetTmp}>

	{* @hook Начало основного блока загрузчика *}
	{hook run='uploader_begin'}

	{block 'uploader_content'}
		{* Drag & drop зона *}
		{include 'components/field/field.upload-area.tpl'
			classes      = 'js-uploader-area'
			inputClasses = 'js-uploader-file'
			inputName    = 'filedata'}

		{* @hook Хук после зоны загрузки *}
		{hook run='uploader_area_after'}

		{* Враппер *}
		<div class="{$component}-wrapper clearfix">
			{* Сайдбар *}
			<div class="{$component}-aside js-uploader-aside">
				{* Блок отображаемый когда нет активного файла *}
				{include 'components/alert/alert.tpl'
					sMods    = 'empty'
					mAlerts  = {lang name='uploader.info.empty'}
					sClasses = "js-{$component}-aside-empty"}

				{* Блоки *}
				<div class="js-uploader-blocks" style="display: none">
					{block 'uploader_aside'}
						{include './uploader-block.info.tpl'}
					{/block}
				</div>
			</div>

			{* Список файлов *}
			<ul class="{$component}-file-list js-uploader-list"></ul>
		</div>
	{/block}

	{* @hook Конец основного блока загрузчика *}
	{hook run='uploader_end'}
</div>