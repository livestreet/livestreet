{**
 * Уведомления
 *
 * @param string  $sAlertTitle       Заголовок
 * @param string  $sAlertStyle       Стиль уведомления (error, info и т.д.), по умолчанию - success
 * @param string  $sAlertAttributes  Дополнительные атрибуты основного блока
 * @param boolean $bAlertVisible     Показывать или нет уведомление, по умолчанию - true
 * @param mixed   $mAlerts           Массив либо строка с текстом уведомления
 *
 * @styles <framework>/css/alerts.css
 *}

<div class="alert alert-{if $sAlertStyle}{$sAlertStyle}{else}success{/if}" 
     {if isset($bAlertVisible) && $bAlertVisible == false}style="display: none"{/if}
     {$sAlertAttributes}>

	{if $sAlertTitle}
		<h4 class="alert--title">{$sAlertTitle}</h4>
	{/if}

	<div class="alert--body">
		{block name='alert_body'}
			{if is_array($mAlerts)}
				<ul class="alert--list">
					{foreach $mAlerts as $aAlert}
						<li class="alert--list--item">{if $aAlert.title}<strong>{$aAlert.title}</strong>:{/if} {$aAlert.msg}</li>
					{/foreach}
				</ul>
			{else}
				{$mAlerts}
			{/if}
		{/block}
	</div>
</div>