{include file='header.tpl'}



<h2 class="page-header">{$aLang.blog_user_readers_all} ({$iCountBlogUsers}): <a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()|escape:'html'}</a></h2>
	

{if $aBlogUsers}
	{assign var="aUsersList" value=[]}
	{foreach from=$aBlogUsers item=oBlogUser}
		{$aUsersList[]=$oBlogUser->getUser()}
	{/foreach}
	{include file='user_list.tpl' aUsersList=$aUsersList sUsersRootPage=$sUsersRootPage}
{else}
	{$aLang.blog_user_readers_empty}
{/if}



{include file='footer.tpl'}