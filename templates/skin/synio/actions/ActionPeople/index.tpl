{extends file='layout.base.tpl'}

{block name='layout_options'}
	{$sNav = 'people'}
{/block}

{block name='layout_content'}
	<h2 class="page-header">{$aLang.people}</h2>

	{include file='forms/form.search.users.tpl'}

	<div id="users-list-search" style="display:none;"></div>

	<div id="users-list-original">
		{router page='people' assign=sUsersRootPage}
		{include file='user_list.tpl' aUsersList=$aUsersRating bUsersUseOrder=true sUsersRootPage=$sUsersRootPage}
	</div>
{/block}