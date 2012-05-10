{include file='header.tpl'}



<h2 class="page-header">{$aLang.blog_user_readers_all} ({$iCountBlogUsers}): <a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()}</a></h2>
	

{if $aBlogUsers}
	{include file='user_list.tpl' aUsersList=$aBlogUsers bUsersUseOrder=true sUsersRootPage=$sUsersRootPage}
	{include file='paging.tpl' aPaging=$aPaging}
{else}
	{$aLang.blog_user_readers_empty}
{/if}



{include file='footer.tpl'}