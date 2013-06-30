{**
 * Список пользователей из определенного города
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{$sNav = 'users'}
{/block}

{block name='layout_page_title'}
	{$aLang.user_list}: <span>{$oCity->getName()|escape:'html'}{if $aPaging} ({$aPaging.iCount}){/if}</span>
{/block}

{block name='layout_content'}
	{include file='user_list.tpl' aUsersList=$aUsersCity}
{/block}