{extends file='layout.base.tpl'}

{block name='layout_options'}
	{$sNav = 'blog'}
{/block}

{block name='layout_content'}
	{include file='topics/topic_list.tpl'}

	{if count($aTopics)}
		{if !$bDisableGetMoreButton}
			<div id="userfeed_loaded_topics"></div>
			<input type="hidden" id="userfeed_last_id" value="{$iUserfeedLastId}" />
			<a class="stream-get-more" id="userfeed_get_more" href="javascript:ls.userfeed.getMore()">{$aLang.userfeed_get_more} &darr;</a>
		{/if}
	{else}
		{$aLang.userfeed_no_events}
	{/if}
{/block}