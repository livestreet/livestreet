{if $aMsgError}
<div id="system_messages_error">
<ul>
{foreach from=$aMsgError item=aMsg}
	<li>
		{if $aMsg.title!=''}
			<b>{$aMsg.title}</b>:
		{/if}
		{$aMsg.msg}
	</li>
{/foreach}
</ul>
</div>
{/if}

{if $aMsgNotice}
<br>
<br>
<div id="system_messages_notice">
<ul>
{foreach from=$aMsgNotice item=aMsg}
	<li>
		{if $aMsg.title!=''}
			<b>{$aMsg.title}</b>:
		{/if}
		{$aMsg.msg}
	</li>
{/foreach}
</ul>
</div>
{/if}