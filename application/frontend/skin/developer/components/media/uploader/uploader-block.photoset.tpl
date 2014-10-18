{**
 * Опции фотосета
 *}

{extends 'components/uploader/uploader-block.tpl'}

{block 'block_options' append}
	{$classes = "{$classes} js-media-info-block"}
	{$attributes = "{$attributes} data-type=\"photoset\""}
{/block}

{block 'block_title'}
	{lang name='media.photoset.settings.title'}
{/block}

{block 'block_content'}
	<form method="post" action="" enctype="multipart/form-data">
		{* Показывать ленту с превьюшками *}
		{include 'components/field/field.checkbox.tpl'
				 name    = 'use_thumbs'
				 checked = true
				 label   = {lang name='media.photoset.settings.fields.use_thumbs.label'}}

		{* Показывать описания фотографий *}
		{include 'components/field/field.checkbox.tpl'
				 name    = 'show_caption'
				 label   = {lang name='media.photoset.settings.fields.show_caption.label'}}
	</form>
{/block}