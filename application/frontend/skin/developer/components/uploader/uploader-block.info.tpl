{**
 * Информация об активном файле
 *}

{extends './uploader-block.tpl'}

{block 'block_options' append}
	{$classes = "{$classes} uploader-info js-uploader-info"}
{/block}

{block 'block_content'}
	{$component_info = 'uploader-info'}

	{* Информация о файле *}
	<div class="{$component_info}-block">

		{* Основная информация о файле *}
		<div class="{$component_info}-base">
			{* Превью *}
			<img src="" alt="" class="{$component_info}-base-image js-{$component_info}-property" data-name="image" width="100" height="100">

			{* Информация *}
			<ul class="{$component_info}-base-properties">
				<li><strong class="{$component_info}-property-name word-wrap js-{$component_info}-property" data-name="name"></strong></li>
				<li class="{$component_info}-property-date js-{$component_info}-property" data-name="date"></li>
				<li><span class="{$component_info}-property-size js-{$component_info}-property" data-name="size"></span></li>
			</ul>
		</div>

		{* Информация о файле *}
		<div class="{$component_info}-group js-{$component_info}-group" data-type="1">
			{* Действия *}
			<ul class="{$component_info}-actions">
				<li><a href="#" class="link-dotted js-{$component_info}-remove">{lang name='uploader.actions.remove'}</a></li>
			</ul>

			{* Уникальные св-ва для каждого типа *}
			<div class="{$component_info}-properties">
				<div class="{$component_info}-type-info">
					Разрешение: <span class="js-{$component_info}-property" data-name="dimensions"></span>
				</div>

				{* Описание *}
				{include 'components/field/field.text.tpl'
					sName  = 'title'
					sInputClasses  = 'js-{$component_info}-property'
					sInputAttributes  = 'data-name="title"'
					sLabel = $aLang.uploadimg_title}
			</div>
		</div>
	</div>
{/block}