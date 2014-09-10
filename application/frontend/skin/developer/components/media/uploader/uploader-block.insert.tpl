{**
 * Опции вставки
 *}

{extends 'components/uploader/uploader-block.tpl'}

{block 'block_options' append}
	{$classes = "{$classes} js-media-info-block"}
	{$attributes = "{$attributes} data-type=\"insert\""}
{/block}

{block 'block_title'}
	{lang name='media.insert.settings.title'}
{/block}

{block 'block_content'}
	{* Выравнивание *}
	{include 'components/field/field.select.tpl'
			 sName  = 'align'
			 sLabel = {lang name='media.image_align.title'}
			 aItems = $imageAlign}

    {* Размер *}
    {$selectImageSizes = [[ 'value' => 'original', 'text' => {lang name='media.insert.settings.fields.size.original'} ]]}

	{foreach Config::Get('module.media.image.sizes') as $aSize}
		{$selectImageSizes[] = [
			'value' => "{$aSize.w}x{$aSize.h}{if $aSize.crop}crop{/if}",
			'text'  => "{$aSize.w} × {if $aSize.h}{$aSize.h}{else}*{/if}"
		]}
	{/foreach}

	{include 'components/field/field.select.tpl'
			 sName          = 'size'
			 sLabel         = {lang name='media.insert.settings.fields.size.label'}
			 sSelectedValue = $_aRequest.blog_category
			 aItems         = $selectImageSizes}
{/block}