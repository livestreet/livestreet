{include file='header.tpl'}

<h2 class="page-header">{$aLang.user_list}: <span>{$oCity->getName()|escape:'html'}</span></h2>

{include file='user_list.tpl' aUsersList=$aUsersCity}

{include file='footer.tpl'}