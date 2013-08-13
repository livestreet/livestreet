{**
 * Список пользователей которые недавно были на сайте
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{$sNav = 'users'}
{/block}

{block name='layout_content'}
	{include file='user_list.tpl' aUsersList=$aUsersLast bTableShowDateLast=true}
	{include file='pagination.tpl' aPaging=$aPaging}
{/block}