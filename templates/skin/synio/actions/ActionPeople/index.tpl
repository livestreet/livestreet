{include file='header.tpl' menu='people'}

<h2 class="page-header">{$aLang.people}</h2>

<form action="" method="POST" id="form-users-search" onsubmit="return false;" class="search-item search-item-abc">
	<div class="search-input-wrapper">
		<input id="search-user-login" type="text" placeholder="{$aLang.user_search_title_hint}" autocomplete="off" name="user_login" value="" class="input-text" onkeyup="ls.timer.run(ls.user.searchUsers,'users_search',['form-users-search'],1000);">
		<div class="input-submit" onclick="jQuery('#form-users-search').submit()"></div>
	</div>
	
	<ul id="user-prefix-filter" class="search-abc">
		<li class="active"><a href="#" onclick="return ls.user.searchUsersByPrefix('',this);"><span>{$aLang.user_search_filter_all}</span></a></li>
		{foreach from=$aPrefixUser item=sPrefixUser}
			<li><a href="#" onclick="return ls.user.searchUsersByPrefix('{$sPrefixUser}',this);"><span>{$sPrefixUser}</span></a></li>
		{/foreach}
	</ul>
</form>


<div id="users-list-search" style="display:none;"></div>

<div id="users-list-original">
	{router page='people' assign=sUsersRootPage}
	{include file='user_list.tpl' aUsersList=$aUsersRating bUsersUseOrder=true sUsersRootPage=$sUsersRootPage}
</div>

{include file='footer.tpl'}