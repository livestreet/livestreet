{**
 * Список новых пользователей
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{$sNav = 'users'}
{/block}

{block name='layout_content'}
	{include file='user_list.tpl' aUsersList=$aUsersRegister}
{/block}