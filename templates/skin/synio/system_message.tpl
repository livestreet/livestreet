{if !$noShowSystemMessage}
	{if $aMsgError}
		<ul class="system-message-error WrapperToClose">
      <div class="TriggerCloseWrapper icon-synio-close"></div>
			{foreach from=$aMsgError item=aMsg}
				<li>
					{if $aMsg.title!=''}
						<strong>{$aMsg.title}</strong>:
					{/if}
					{$aMsg.msg}
				</li>
			{/foreach}
		</ul>
	{/if}


	{if $aMsgNotice}
		<ul class="system-message-notice WrapperToClose">
      <div class="TriggerCloseWrapper icon-synio-close"></div>
			{foreach from=$aMsgNotice item=aMsg}
				<li>
					{if $aMsg.title!=''}
						<strong>{$aMsg.title}</strong>:
					{/if}
					{$aMsg.msg}
				</li>
			{/foreach}
		</ul>
	{/if}
{/if}