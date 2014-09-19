{extends './pane.tpl'}

{block 'media_pane_options' append}
	{$id = 'tab-media-insert'}
{/block}

{block 'media_pane_content'}
	{include './../uploader/uploader.tpl'
		attributes   = 'id="media-uploader"'
		classes      = 'js-media-uploader'
		targetParams = $aTargetParams
		targetType   = $sMediaTargetType
		targetId     = $sMediaTargetId
		targetTmp    = $sMediaTargetTmp}
{/block}

{block 'media_pane_footer' prepend}
	{include 'components/button/button.tpl'
		sMods    = 'primary'
		sClasses = 'js-media-insert-submit'
		sText    = {lang name='media.insert.submit'}}
{/block}