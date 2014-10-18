{**
 * Страница с формой поиска
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options'}
	{$bNoSidebar = true}
{/block}

{block 'layout_page_title'}
	{$aLang.search.search}
{/block}

{block 'layout_content'}
	{include 'components/search/search-form.main.tpl'}
	{include 'navs/nav.search.tpl'}

	{if $aResultItems}
		{if $sSearchType == 'topics'}
			{include 'components/topic/topic-list.tpl' topics=$aResultItems paging=$aPaging}
		{elseif $sSearchType == 'comments'}
			{include 'comments/comment_list.tpl' aComments=$aResultItems}
		{else}
			{hook run='search_result' type=$sSearchType}
		{/if}
	{elseif $_aRequest.q}
		{include 'components/alert/alert.tpl' text=$aLang.search.alerts.empty mods='empty'}
	{/if}
{/block}