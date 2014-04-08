{**
 * Лента пользователя
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{$sNav = 'topics'}
{/block}

{block name='layout_content'}
	{if $aTopics}
		<div id="userfeed-topic-list">
			{include 'topics/topic_list.tpl'}
		</div>

		{if ! $bDisableGetMoreButton}
			{include 'components/more/more.tpl'
					 sClasses    = "js-more-userfeed"
					 sAttributes = "data-proxy-i-last-id=\"{$iUserfeedLastId}\""}
		{/if}
	{else}
		{include 'alert.tpl' mAlerts=$aLang.common.empty sAlertStyle='empty'}
	{/if}
{/block}