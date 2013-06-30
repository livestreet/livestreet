{**
 * Системные сообщения
 *}

{if ! $bNoSystemMessages}
	{if $aMsgError}
		<ul class="alert alert-error">
			{foreach from=$aMsgError item=aMsg}
				<li>
					{if $aMsg.title != ''}
						<strong>{$aMsg.title}</strong>:
					{/if}

					{$aMsg.msg}
				</li>
			{/foreach}
		</ul>
	{/if}


	{if $aMsgNotice}
		<ul class="alert alert-success">
			{foreach from=$aMsgNotice item=aMsg}
				<li>
					{if $aMsg.title != ''}
						<strong>{$aMsg.title}</strong>:
					{/if}

					{$aMsg.msg}
				</li>
			{/foreach}
		</ul>
	{/if}
{/if}