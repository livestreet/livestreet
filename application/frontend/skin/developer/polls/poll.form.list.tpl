{**
 * Список добавленных опросов в форме добавления
 *
 * @styles poll.css
 * @scripts <common>/js/poll.js
 *}

<ul class="poll-form-list js-poll-form-list">
	{if $aPollItems}
		{foreach $aPollItems as $oPoll}
			{include 'polls/poll.form.list.item.tpl' oPoll=$oPoll}
		{/foreach}
	{/if}
</ul>