{**
 * Уведомления
 *}

<div class="alert alert-{if $sAlertStyle}{$sAlertStyle}{else}success{/if}" {$sAlertAttributes}>
	{if $sAlertTitle}
		<h4 class="alert--title">{$sAlertTitle}</h4>
	{/if}

	<div class="alert--body">
		{block name='alert_body'}
			{if is_array($mAlerts)}
				<ul>
					{foreach $mAlerts as $aAlert}
						<li>{if $aAlert.title}<strong>{$aAlert.title}</strong>:{/if} {$aAlert.msg}</li>
					{/foreach}
				</ul>
			{else}
				{$mAlerts}
			{/if}
		{/block}
	</div>
</div>