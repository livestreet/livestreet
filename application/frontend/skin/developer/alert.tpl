{**
 * Уведомления
 *
 * @param string  $sAlertTitle            Заголовок
 * @param mixed   $mAlerts                Массив либо строка с текстом уведомления
 * @param string  $sAlertMods (success)   Модификаторы (error, info и т.д.)
 * @param string  $sAlertAttributes       Дополнительные атрибуты основного блока
 * @param string  $sAlertClasses          Дополнительные классы
 * @param bool    $bAlertVisible (true)   Показывать или нет уведомление
 * @param bool    $bAlertClose (true)     Показывать или нет кнопку закрытия
 *
 * @styles <framework>/css/alerts.css
 *}

{* Название компонента *}
{$_sComponentName = 'alert'}

{* Дефолтный модификатор *}
{$_sComponentDefaultMod = 'success'}

{* Строка которой разделяются имя компонента и модификатор *}
{$_sModifierDelimiter = '--'}

{* Временный костыль *}
{$sAlertMods = $sAlertStyle}

{* Формируем модификаторы *}
{$aMods = " "|explode:($sAlertMods|default:$_sComponentDefaultMod|strip)}

{foreach $aMods as $sMod}
	{if $sMod}
		{$sResultMods = "$sResultMods $_sComponentName$_sModifierDelimiter$sMod"}
	{/if}
{/foreach}


{* Уведомление *}
<div class="{$_sComponentName} {$sResultMods} {$sAlertClasses} js-{$_sComponentName}" {if ! $bAlertVisible|default:true}style="display: none"{/if} {$sAlertAttributes}>
	{* Заголовок *}
	{if $sAlertTitle}
		<h4 class="{$_sComponentName}-title">{$sAlertTitle}</h4>
	{/if}

	{* Кнопка закрытия *}
	{if $bAlertClose}
		<div class="{$_sComponentName}-close" data-type="alert-close">×</div>
	{/if}

	{* Контент *}
	<div class="{$_sComponentName}-body">
		{block name='alert_body'}
			{if is_array($mAlerts)}
				<ul class="{$_sComponentName}-list">
					{foreach $mAlerts as $aAlert}
						<li class="{$_sComponentName}-list-item">
							{if $aAlert.title}
								<strong>{$aAlert.title}</strong>:
							{/if}

							{$aAlert.msg}
						</li>
					{/foreach}
				</ul>
			{else}
				{$mAlerts}
			{/if}
		{/block}
	</div>
</div>