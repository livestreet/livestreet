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
			{include 'more.tpl' sLoadClasses="js-more-userfeed" iLoadLastId=$iUserfeedLastId}
		{/if}
	{else}
		{include 'alert.tpl' mAlerts=$aLang.common.empty sAlertStyle='empty'}
	{/if}
{/block}