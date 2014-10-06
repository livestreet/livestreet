{extends './pane.tpl'}

{block 'media_pane_options' append}
	{$id = 'tab-media-url'}
{/block}

{block 'media_pane_content'}
	<form method="post" action="" enctype="multipart/form-data" class="mb-20 js-media-url-form">
		{* Типы файлов *}
		{* TODO: Add hook *}
		{include 'components/field/field.select.tpl'
			sName          = 'filetype'
			sLabel         = 'Type'
			sInputClasses  = 'width-300 js-media-url-type'
			aItems         = [
				[ 'value' => '1', 'text' => 'Image' ]
			]}

		{* Ссылка *}
		{include 'components/field/field.text.tpl'
				 sName    = 'url'
				 sValue   = 'http://'
				 sInputClasses = 'js-media-url-form-url'
				 sLabel   = {lang 'media.url.fields.url.label'}}
	</form>

	<div class="js-media-url-settings-blocks">
		{include '../uploader/uploader-block.insert.image.tpl'}
	</div>
{/block}

{block 'media_pane_footer' prepend}
	{include 'components/button/button.tpl'
		sMods    = 'primary'
		sClasses = 'js-media-url-submit-insert'
		sText    = {lang 'media.url.submit_insert'}}

	{include 'components/button/button.tpl'
		sMods    = 'primary'
		sClasses = 'js-media-url-submit-upload'
		sText    = {lang 'media.url.submit_upload'}}
{/block}