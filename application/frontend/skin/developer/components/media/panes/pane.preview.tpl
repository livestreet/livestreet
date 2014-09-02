{extends './pane.tpl'}

{block 'media_pane_options' append}
	{$id = 'tab-media-preview'}
{/block}

{block 'media_pane_content'}
	{if $aTargetItems}
		{foreach $aTargetItems as $oTarget}
			<p class="mb-20">
				<a href="#" class="button" onclick="ls.media.removePreviewFile({$oTarget->getMediaId()}); return false;">Удалить превью</a>
			</p>

			{$aPreview = $oTarget->getPreviewImageItemsWebPath()}

			{foreach $aPreview as $sPreviewFile}
				<img src="{$sPreviewFile}" alt=""><br>
			{/foreach}
		{/foreach}
	{else}
		Превью можно <a href="#" onclick="jQuery('.js-tab-show-gallery').first().click(); return false;">выбрать из галереи</a>.
	{/if}
{/block}