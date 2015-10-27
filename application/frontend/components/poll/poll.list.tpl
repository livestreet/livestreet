{**
 * Список опросов
 *
 * @param array $polls
 *}

{foreach $polls as $poll}
    {component 'poll' poll=$poll}
{/foreach}