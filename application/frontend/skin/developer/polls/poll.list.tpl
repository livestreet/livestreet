{**
 * Список опросов
 *
 * @styles poll.css
 * @scripts <common>/js/poll.js
 *}

{foreach $aPollItems as $oPoll}
	{include 'polls/poll.tpl'}
{/foreach}