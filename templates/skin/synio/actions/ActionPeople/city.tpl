{include file='header.tpl'  menu='people'}

<h2 class="page-header">{$aLang.user_list}: <span>{$oCity->getName()|escape:'html'}{if $aPaging} ({$aPaging.iCount}){/if}</span></h2>

{include file='user_list.tpl' aUsersList=$aUsersCity}

{include file='footer.tpl'}