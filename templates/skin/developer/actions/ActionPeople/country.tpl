{include file='header.tpl'}

<h2 class="page-header">{$aLang.user_list}: <span>{$oCountry->getName()|escape:'html'}</span></h2>

{include file='user_list.tpl' aUsersList=$aUsersCountry}

{include file='footer.tpl'}