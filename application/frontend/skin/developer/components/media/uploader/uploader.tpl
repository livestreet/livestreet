{extends 'components/uploader/uploader.tpl'}

{block 'uploader_aside' append}
	{* Основные настройки *}
	<div class="uploader-info-block js-media-info-block" data-type="insert" style="display: none;">
		<h3>Опции вставки</h3>

		{* Выравнивание *}
		{include 'components/field/field.select.tpl'
				 sName  = 'align'
				 sLabel = $aLang.uploadimg_align
				 aItems = $aSelectImageAlign}

        {* Размер *}
        {$selectImageSizes = [ [ 'value' => 'original', 'text' => 'Оригинал' ] ]}

		{foreach Config::Get('module.media.image.sizes') as $aSize}
			{$selectImageSizes[] = [
				'value' => "{$aSize.w}x{$aSize.h}{if $aSize.crop}crop{/if}",
				'text'  => "{$aSize.w} × {if $aSize.h}{$aSize.h}{else}*{/if}"
			]}
		{/foreach}

		{include 'components/field/field.select.tpl'
				 sName          = 'size'
				 sLabel         = 'Размер'
				 sSelectedValue = $_aRequest.blog_category
				 aItems         = $selectImageSizes}
	</div>

	{* Опции фотосета *}
    <div class="uploader-info-block js-media-info-block" data-type="photoset" style="display: none;">
		<h3>Опции фотосета</h3>

		{* Показывать ленту с превьюшками *}
		{include 'components/field/field.checkbox.tpl'
				 sName    = 'use_thumbs'
				 bChecked = true
				 sLabel   = 'Показывать ленту с превьюшками'}

		{* Показывать описания фотографий *}
		{include 'components/field/field.checkbox.tpl'
				 sName    = 'show_caption'
				 sLabel   = 'Показывать описания фотографий'}
    </div>
{/block}