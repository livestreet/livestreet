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
				 sLabel = 'Ссылка'}

		{* Описание *}
		{include 'components/field/field.text.tpl'
				 sName  = 'title'
				 sLabel = $aLang.uploadimg_title}

		<div style="display: none;" class="js-media-link-settings-image">
			<p><img src="" width="200" class="js-media-link-settings-image-preview"></p>

			{* Выравнивание *}
			{include 'components/field/field.select.tpl'
					 sName    = 'align'
					 sClasses = 'width-200'
					 sLabel   = $aLang.uploadimg_align
					 aItems   = $aSelectImageAlign}
		</div>
	</form>
{/block}

{block 'media_pane_footer' prepend}
	<button type="submit" class="button button--primary js-media-link-insert-button">Вставить как ссылку</button>
	<button type="submit" class="button button--primary js-media-link-upload-button">Загрузить и вставить</button>
{/block}