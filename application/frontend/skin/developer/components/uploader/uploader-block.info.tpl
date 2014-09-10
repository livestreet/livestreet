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

		{* Информация о изображении *}
		{include './uploader-block.info-group.tpl'
			type             = '1'
			properties       = [[ 'name' => 'dimensions', 'label' => {lang name='uploader.info.types.image.dimensions'} ]]
			propertiesFields = [[ 'name' => 'title', 'label' => {lang name='uploader.info.types.image.title'} ]]}

		{* @hook Конец блока с информацией о файле *}
		{hook run='uploader_info_end'}
	</div>
{/block}