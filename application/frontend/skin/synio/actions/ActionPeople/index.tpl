{**
 * Список всех пользователей
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{$sNav = 'users'}
{/block}

{block name='layout_page_title'}{$aLang.people}{/block}

{block name='layout_content'}
	{include file='forms/form.search.users.tpl'}

	<div id="users-list-search" style="display:none;"></div>

	<div id="users-list-original">
		{router page='people' assign=sUsersRootPage}
		{include file='user_list.tpl' aUsersList=$aUsersRating bUsersUseOrder=true sUsersRootPage=$sUsersRootPage}
	</div>
{/block}