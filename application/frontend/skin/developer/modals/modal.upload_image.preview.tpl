{if $aTargetItems}

	{foreach $aTargetItems as $oTarget}
		{$aPreview=$oTarget->getPreviewImageItemsWebPath()}
		{foreach $aPreview as $sPreviewFile}
			<img src="{$sPreviewFile}" alt="">
		{/foreach}
		<a href="#" onclick="ls.media.removePreviewFile({$oTarget->getMediaId()}); return false;">Удалить превью</a>
	{/foreach}

{else}
	Превью можно <a href="#" onclick="jQuery('.js-tab-show-gallery').first().click(); return false;">выбрать из галереи</a>.
{/if}