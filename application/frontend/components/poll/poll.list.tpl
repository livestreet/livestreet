{**
 * Список опросов
 *
 * @param array $polls
 *}

{foreach $polls as $poll}
	{include './poll.tpl' poll=$poll}
{/foreach}