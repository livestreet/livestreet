{**
 * Лента пользователя
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{$sNav = 'topics'}
{/block}

{block name='layout_content'}
	{include file='topics/topic_list.tpl'}

	{if count($aTopics)}
	    {if ! $bDisableGetMoreButton}
	        <div id="userfeed_loaded_topics"></div>
	        <input type="hidden" id="userfeed_last_id" value="{$iUserfeedLastId}" />

	        <div class="get-more" id="userfeed_get_more" onclick="ls.userfeed.getMore()">{$aLang.userfeed_get_more} &darr;</div>
	    {/if}
	{else}
	    {$aLang.userfeed_no_events}
	{/if}
{/block}