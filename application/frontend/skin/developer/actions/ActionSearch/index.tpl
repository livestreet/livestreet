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

	{if $bIsResults}
		{include 'navs/nav.search.tpl'}

		{if $aReq.sType == 'topics'}
			{include file='topics/topic_list.tpl'}
		{elseif $aReq.sType == 'comments'}
			{include file='comments/comment_list.tpl'}
		{else}
			{hook run='search_result' sType=$aReq.sType}
		{/if}
	{elseif $aReq.q}
		{include 'components/alert/alert.tpl' mAlerts=$aLang.search_results_empty aMods='empty'}
	{/if}
{/block}