{**
 * Список пользователей
 *
 * @param array $users
 *}

{foreach $smarty.local.users as $user}
	{component 'user' template='list-loop' user=$user}
{/foreach}