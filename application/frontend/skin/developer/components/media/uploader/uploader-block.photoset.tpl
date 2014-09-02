{**
 * Опции фотосета
 *}

{extends 'components/uploader/uploader-block.tpl'}

{block 'block_options' append}
	{$classes = "{$classes} js-media-info-block"}
	{$attributes = "{$attributes} data-type=\"photoset\""}
{/block}

{block 'block_title'}
	Опции фотосета
{/block}

{block 'block_content'}
	{* Показывать ленту с превьюшками *}
	{include 'components/field/field.checkbox.tpl'
			 sName    = 'use_thumbs'
			 bChecked = true
			 sLabel   = 'Показывать ленту с превьюшками'}

	{* Показывать описания фотографий *}
	{include 'components/field/field.checkbox.tpl'
			 sName    = 'show_caption'
			 sLabel   = 'Показывать описания фотографий'}
{/block}