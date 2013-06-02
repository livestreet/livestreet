{include file='header.tpl' sMenuHeadItemSelect="blogs"}

<h2 class="page-header">{$aLang.blogs}</h2>

{include file='form.search.blogs.tpl'}

<div id="blogs-list-search" style="display:none;"></div>

<div id="blogs-list-original">
	{if ! $sBlogsRootPage}
		{router page='blogs' assign=sBlogsRootPage}
	{/if}

	{include file='blog_list.tpl' bBlogsUseOrder=true sBlogsRootPage=$sBlogsRootPage}
	{include file='paging.tpl' aPaging=$aPaging}
</div>

{include file='footer.tpl'}