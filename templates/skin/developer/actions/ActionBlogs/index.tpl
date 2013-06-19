{**
 * Список блогов
 *}

{extends file='layout.base.tpl'}

{block name='layout_options'}
	{$sMenuHeadItemSelect = 'blogs'}
{/block}

{block name='layout_page_title'}{$aLang.blogs}{/block}

{block name='layout_content'}
	{include file='forms/form.search.blogs.tpl'}

	<div id="blogs-list-search" style="display:none;"></div>

	<div id="blogs-list-original">
		{if ! $sBlogsRootPage}
			{router page='blogs' assign=sBlogsRootPage}
		{/if}

		{include file='blog_list.tpl' bBlogsUseOrder=true sBlogsRootPage=$sBlogsRootPage}
		{include file='paging.tpl' aPaging=$aPaging}
	</div>
{/block}