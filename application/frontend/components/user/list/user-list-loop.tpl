{**
 * Список пользователей
 *
 * @param array $users
 *}

{foreach $smarty.local.users as $user}
	{include './user-list-item.tpl' user=$user}
{/foreach}