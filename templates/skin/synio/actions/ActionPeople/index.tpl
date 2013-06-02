{include file='header.tpl' nav='people'}

<h2 class="page-header">{$aLang.people}</h2>

{include file='form.search.users.tpl'}

<div id="users-list-search" style="display:none;"></div>

<div id="users-list-original">
	{router page='people' assign=sUsersRootPage}
	{include file='user_list.tpl' aUsersList=$aUsersRating bUsersUseOrder=true sUsersRootPage=$sUsersRootPage}
</div>

{include file='footer.tpl'}