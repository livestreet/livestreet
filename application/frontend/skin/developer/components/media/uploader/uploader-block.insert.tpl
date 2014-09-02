{**
 * Опции вставки
 *}

{extends 'components/uploader/uploader-block.tpl'}

{block 'block_options' append}
	{$classes = "{$classes} js-media-info-block"}
	{$attributes = "{$attributes} data-type=\"insert\""}
{/block}

{block 'block_title'}
	Опции вставки
{/block}

{block 'block_content'}
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
{/block}