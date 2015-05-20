{**
 * Список добавленных опросов в форме добавления
 *
 * @styles poll.css
 * @scripts <common>/js/poll.js
 *}

<ul class="ls-poll-manage-list js-poll-manage-list">
	{if $aPollItems}
		{foreach $aPollItems as $oPoll}
			{include './poll.manage.item.tpl' oPoll=$oPoll}
		{/foreach}
	{/if}
</ul>