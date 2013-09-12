{**
 * Список пользователей которые подключены к блогу
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_page_title'}
	{$aLang.blog_user_readers_all} ({$iCountBlogUsers}): <a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()|escape:'html'}</a>
{/block}

{block name='layout_content'}
	{if $aBlogUsers}
		{$aUsersList = []}
		{foreach $aBlogUsers as $oBlogUser}
			{$aUsersList[]=$oBlogUser->getUser()}
		{/foreach}
		{include file='user_list.tpl' aUsersList=$aUsersList sUsersRootPage=$sUsersRootPage}
	{else}
		{$aLang.blog_user_readers_empty}
	{/if}
{/block}