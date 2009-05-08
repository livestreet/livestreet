{if $aMsgError}
<div class="system-messages-error">
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
<div class="system-messages-notice">
	<ul>
	{foreach from=$aMsgNotice item=aMsg}
		<li>
			{if $aMsg.title!=''}
				<strong>{$aMsg.title}</strong>:
			{/if}
			{$aMsg.msg}
		</li>
	{/foreach}
	</ul>
</div>
{/if}