{**
 * Уведомления
 *
 * @param string  $sTitle            Заголовок
 * @param mixed   $mAlerts           Массив либо строка с текстом уведомления
 * @param string  $sMods (success)   Модификаторы (error, info и т.д.)
 * @param string  $sAttributes       Дополнительные атрибуты основного блока
 * @param string  $sClasses          Дополнительные классы
 * @param bool    $bVisible (true)   Показывать или нет уведомление
 * @param bool    $bClose (true)     Показывать или нет кнопку закрытия
 *
 * @styles <framework>/css/alerts.css
 *}

{* Название компонента *}
{$_sComponentName = 'alert'}

{* Дефолтный модификатор *}
{$_sComponentDefaultMod = 'success'}


{* Уведомление *}
<div class="{$_sComponentName} {mod name=$_sComponentName mods=$sMods default=$_sComponentDefaultMod} {$smarty.local.sClasses} js-{$_sComponentName}"
	{if ! $smarty.local.bVisible|default:true}style="display: none"{/if}
	{$smarty.local.sAttributes}
	role="alert">

	{* Заголовок *}
	{if $sTitle}
		<h4 class="{$_sComponentName}-title">{$sTitle}</h4>
	{/if}

	{* Кнопка закрытия *}
	{if $bClose}
		<div class="{$_sComponentName}-close" data-type="alert-close">×</div>
	{/if}

	{* Контент *}
	<div class="{$_sComponentName}-body">
		{block name='alert_body'}
			{if is_array($smarty.local.mAlerts)}
				<ul class="{$_sComponentName}-list">
					{foreach $smarty.local.mAlerts as $aAlert}
						<li class="{$_sComponentName}-list-item">
							{if $aAlert.title}
								<strong>{$aAlert.title}</strong>:
							{/if}

							{$aAlert.msg}
						</li>
					{/foreach}
				</ul>
			{else}
				{$smarty.local.mAlerts}
			{/if}
		{/block}
	</div>
</div>