{**
 * Список пользователей которые подключены к блогу
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options'}
	{$bNoSidebar = true}
{/block}

{block 'layout_page_title'}
	{$aLang.blog.users.readers_all} ({$iCountBlogUsers}):
	<a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()|escape}</a>
{/block}

{block 'layout_content'}
	{include 'components/user/user-list.tpl' users=$aBlogUsers pagination=$aPaging}
{/block}