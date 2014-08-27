{**
 * Информация об активном файле
 *}

{$component = 'uploader-info'}

{block 'uploader_info_options'}{/block}

{* Информация о выделенном файле *}
<div class="{$component} js-media-info">
	{* Блок отображаемый когда нет выделенных файлов *}
	{include 'components/alert/alert.tpl' sMods='empty' mAlerts={lang name='uploader.info.empty'} sClasses='js-media-info-empty'}

	{* Информация о файле *}
	<div class="{$component}-block js-media-properties" style="display: none;">

		{* Основная информация о файле *}
		<div class="{$component}-base">
			{* Превью *}
			<img src="" alt="" class="{$component}-base-image js-media-info-property" data-name="image" width="100" height="100">

			{* Информация *}
			<ul class="{$component}-base-properties">
				<li><strong class="word-wrap js-media-info-property" data-name="name"></strong></li>
				<li class="js-media-info-property" data-name="date"></li>
				<li><span class="js-media-info-property" data-name="size"></span></li>
			</ul>
	    </div>

		{* Информация о файле *}
		<div class="{$component}-type js-media-info-group" data-type="1" style="display: none;">
			<ul class="{$component}-actions">
				<li><a href="#" class="link-dotted js-media-item-info-remove">{lang name='uploader.actions.remove'}</a></li>
			</ul>

			<div class="{$component}-type-info">
				Разрешение: <span class="js-media-info-property" data-name="dimensions"></span>
			</div>

			{* Описание *}
			{include 'components/field/field.text.tpl'
				sName  = 'title'
				sInputClasses  = 'js-media-info-property'
				sInputAttributes  = 'data-name="title"'
				sLabel = $aLang.uploadimg_title}
	    </div>
    </div>
</div>