{extends './pane.tpl'}

{block 'media_pane_options' append}
	{$id = 'tab-media-url'}
{/block}

{block 'media_pane_content'}
	<form method="POST" action="" enctype="multipart/form-data" class="js-media-link-form">
		{* Ссылка *}
		{include 'components/field/field.text.tpl'
				 sName  = 'url'
				 sValue = 'http://'
				 sLabel = {lang name='media.url.fields.url.label'}}

		{* Описание *}
		{include 'components/field/field.text.tpl'
				 sName  = 'title'
				 sLabel = {lang name='media.url.fields.title.label'}}

		<div style="display: none;" class="js-media-link-settings-image">
			<p><img src="" width="200" class="js-media-link-settings-image-preview"></p>

			{* Выравнивание *}
			{include 'components/field/field.select.tpl'
					 sName    = 'align'
					 sClasses = 'width-200'
					 sLabel   = {lang name='media.image_align.title'}
					 aItems   = $imageAlign}
		</div>
	</form>
{/block}

{block 'media_pane_footer' prepend}
	{include 'components/button/button.tpl'
		sMods    = 'primary'
		sClasses = 'js-media-link-insert-button'
		sText    = {lang name='media.url.submit_insert'}}

	{include 'components/button/button.tpl'
		sMods    = 'primary'
		sClasses = 'js-media-link-upload-button'
		sText    = {lang name='media.url.submit_upload'}}
{/block}