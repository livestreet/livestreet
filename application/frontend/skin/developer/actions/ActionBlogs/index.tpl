{**
 * Список блогов
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options'}
	{$sMenuHeadItemSelect = 'blogs'}
{/block}

{block 'layout_page_title'}{$aLang.blog.blogs}{/block}

{block 'layout_content'}
	{include 'forms/form.search.blogs.tpl'}

	<div id="blogs-list-search" style="display:none;"></div>

	<div id="blogs-list-original">
		{if ! $sBlogsRootPage}
			{router page='blogs' assign=sBlogsRootPage}
		{/if}

		{include 'actions/ActionBlogs/blog_list.tpl' bBlogsUseOrder=true sBlogsRootPage=$sBlogsRootPage}
		{include 'pagination.tpl' aPaging=$aPaging}
	</div>
{/block}