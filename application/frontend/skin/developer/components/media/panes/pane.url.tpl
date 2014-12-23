{extends './pane.tpl'}

{block 'media_pane_options' append}
	{$id = 'tab-media-url'}
{/block}

{block 'media_pane_content'}
	<form method="post" action="" enctype="multipart/form-data" class="mb-20 js-media-url-form">
		{* Типы файлов *}
		{* TODO: Add hook *}
		{include 'components/field/field.select.tpl'
			name          = 'filetype'
			label         = 'Type'
			inputClasses  = 'width-300 js-media-url-type'
			items         = [
				[ 'value' => '1', 'text' => 'Image' ]
			]}

		{* Ссылка *}
		{include 'components/field/field.text.tpl'
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
	{include 'components/button/button.tpl'
		mods    = 'primary'
		classes = 'js-media-url-submit-insert'
		text    = {lang 'media.url.submit_insert'}}

	{include 'components/button/button.tpl'
		mods    = 'primary'
		classes = 'js-media-url-submit-upload'
		text    = {lang 'media.url.submit_upload'}}
{/block}