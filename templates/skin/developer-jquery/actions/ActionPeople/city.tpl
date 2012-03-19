{include file='header.tpl' menu='people'}

<h2 class="page-header">{$aLang.user_list}: {$oCity->getName()|escape:'html'}</h2>

{include file='user_list.tpl' aUsersList=$aUsersCity}

{include file='footer.tpl'}