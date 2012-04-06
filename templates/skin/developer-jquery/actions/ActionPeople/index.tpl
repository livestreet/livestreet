{include file='header.tpl' menu='people'}

<form action="" method="POST" id="form-users-search" onsubmit="return false;" class="search search-item">
	<input type="text" placeholder="{$aLang.user_search_title_hint}" autocomplete="off" name="user_login" value="" class="input-text" onkeyup="ls.timer.run(ls.user.searchUsers,'users_search',['form-users-search'],1000);">
</form>

<ul id="user-prefix-filter">
	<li class="active"><a href="#" onclick="return ls.user.searchUsersByPrefix('',this);">Все</a></li>
	{foreach from=$aPrefixUser item=sPrefixUser}
		<li><a href="#" onclick="return ls.user.searchUsersByPrefix('{$sPrefixUser}',this);">{$sPrefixUser}</a></li>
	{/foreach}
</ul>

<div id="users-list-search" style="display:none;"></div>

<div id="users-list-original">
	{router page='people' assign=sUsersRootPage}
	{include file='user_list.tpl' aUsersList=$aUsersRating bUsersUseOrder=true sUsersRootPage=$sUsersRootPage}
</div>

{include file='footer.tpl'}