{**
 * Список пользователей которые подключены к блогу
 *}

{extends 'layouts/layout.base.tpl'}

{block'layout_page_title'}
	{$aLang.blog.users.readers_all} ({$iCountBlogUsers}): 
	<a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()|escape}</a>
{/block}

{block'layout_content'}
	{include 'user_list.tpl' aUsersList=$aBlogUsers bUsersUseOrder=true sUsersRootPage=$sUsersRootPage}
{/block}