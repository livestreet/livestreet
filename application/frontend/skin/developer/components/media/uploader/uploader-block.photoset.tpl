{**
 * Опции фотосета
 *}

{extends 'Component@uploader.uploader-block'}

{block 'block_options' append}
	{$classes = "{$classes} js-media-info-block"}
	{$attributes = array_merge( $attributes|default:[], [ 'data-type' => 'photoset' ] )}
{/block}

{block 'block_title'}
	{lang name='media.photoset.settings.title'}
{/block}

{block 'block_content'}
	<form method="post" action="" enctype="multipart/form-data">
		{* Показывать ленту с превьюшками *}
		{component 'field' template='checkbox'
				 name    = 'use_thumbs'
				 checked = true
				 label   = {lang name='media.photoset.settings.fields.use_thumbs.label'}}

		{* Показывать описания фотографий *}
		{component 'field' template='checkbox'
				 name    = 'show_caption'
				 label   = {lang name='media.photoset.settings.fields.show_caption.label'}}
	</form>
{/block}