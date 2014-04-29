{**
 * Страница с формой поиска
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{$bNoSidebar = true}
{/block}

{block name='layout_page_title'}
	{$aLang.search.search}
{/block}

{block name='layout_content'}
	{include 'forms/form.search.main.tpl'}
	{include 'navs/nav.search.tpl'}

	{if $aResultItems}
		{if $sSearchType == 'topics'}
			{include file='topics/topic_list.tpl' aTopics=$aResultItems}
		{elseif $sSearchType == 'comments'}
			{include file='comments/comment_list.tpl' aComments=$aResultItems}
		{else}
			{hook run='search_result' sType=$sSearchType}
		{/if}
	{elseif $_aRequest.q}
		{include 'components/alert/alert.tpl' mAlerts=$aLang.search.alerts.empty aMods='empty'}
	{/if}
{/block}