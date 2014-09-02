{extends './pane.tpl'}

{block 'media_pane_options' append}
	{$id = 'tab-media-insert'}
{/block}

{block 'media_pane_content'}
	{include './../uploader/uploader.tpl'
		attributes   = 'id="media-uploader"'
		classes      = 'js-media-gallery'
		targetParams = $aTargetParams
		targetType   = $sMediaTargetType
		targetId     = $sMediaTargetId
		targetTmp    = $sMediaTargetTmp}
{/block}

{block 'media_pane_footer' prepend}
	<button type="submit" class="button button--primary js-media-insert-button js-media-insert">Вставить</button>
{/block}