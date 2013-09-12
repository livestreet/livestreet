{**
 * Список блогов
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{$sMenuHeadItemSelect = 'blogs'}
{/block}

{block name='layout_page_title'}{$aLang.blogs}{/block}

{block name='layout_content'}
	{include file='forms/form.search.blogs.tpl'}

	<div id="blogs-list-search" style="display:none;"></div>

	<div id="blogs-list-original">
		{if !$sBlogsRootPage}
			{router page='blogs' assign=sBlogsRootPage}
		{/if}
		{include file='actions/ActionBlogs/blog_list.tpl' bBlogsUseOrder=true sBlogsRootPage=$sBlogsRootPage}
		{include file='pagination.tpl' aPaging=$aPaging}
	</div>
{/block}