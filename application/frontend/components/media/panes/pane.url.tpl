{extends './pane.tpl'}

{block 'media_pane_options' append}
	{$id = 'tab-media-url'}
{/block}

{block 'media_pane_content'}
	<form method="post" action="" enctype="multipart/form-data" class="mb-20 js-media-url-form">
		{* Типы файлов *}
		{* TODO: Add hook *}
		{component 'field' template='select'
			name          = 'filetype'
			label         = 'Type'
			inputClasses  = 'width-300 js-media-url-type'
			items         = [
				[ 'value' => '1', 'text' => 'Image' ]
			]}

		{* Ссылка *}
		{component 'field' template='text'
				 name    = 'url'
				 value   = 'http://'
				 inputClasses = 'js-media-url-form-url'
				 label   = {lang 'media.url.fields.url.label'}}
	</form>

	<div class="mb-15 js-media-url-image-preview" style="display: none"></div>

	<div class="js-media-url-settings-blocks">
		{include '../uploader/uploader-block.insert.image.tpl' useSizes=false}
	</div>
{/block}

{block 'media_pane_footer' prepend}
	{component 'button'
		mods    = 'primary'
		classes = 'js-media-url-submit-insert'
		text    = {lang 'media.url.submit_insert'}}

	{component 'button'
		mods    = 'primary'
		classes = 'js-media-url-submit-upload'
		text    = {lang 'media.url.submit_upload'}}
{/block}