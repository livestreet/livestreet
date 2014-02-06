<div id="poll-form-items">
	{if $aPollItems}
		{foreach $aPollItems as $oPoll}
			{include file="polls/poll.form.item.tpl" oPoll=$oPoll}
		{/foreach}
	{/if}
</div>