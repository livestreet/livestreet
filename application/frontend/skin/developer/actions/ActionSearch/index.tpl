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

	{if $aResultItems}
		<ul class="nav nav-pills">
			<li {if $sSearchType == 'topics'}class="active"{/if}>
				<a href="{router page='search/topics'}?q={$_aRequest.q}">
					{$aLang.search.result.topics}
					{if $aTypeCounts.topics}
						({$aTypeCounts.topics})
					{/if}
				</a>
			</li>
			<li {if $sSearchType == 'comments'}class="active"{/if}>
				<a href="{router page='search/comments'}?q={$_aRequest.q}">
					{$aLang.search.result.comments}
					{if $aTypeCounts.comments}
						({$aTypeCounts.comments})
					{/if}
				</a>
			</li>
			{hook run='search_result_item' sType=$sSearchType aTypeCounts=$aTypeCounts}
		</ul>

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