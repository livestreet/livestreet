{**
 * Базовый шаблон поля формы
 *
 * @param string  sName      Имя поля (параметр name)
 * @param string  sLabel     Текст лэйбла
 * @param string  sNote      Подсказка (отображается под полем)
 * @param string  aRules     Правила валидации
 *}

{* Название компонента *}
{$_sComponentName = 'field'}

{* Уникальный ID *}
{$_uid = $smarty.local.sId|default:($_sComponentName|cat:rand(0, 10e10))}

{* Переменные *}
{$_sMods = $smarty.local.sMods}
{$_sValue = $smarty.local.sValue}
{$_sInputClasses = $smarty.local.sInputClasses}
{$_sInputAttributes = $smarty.local.sInputAttributes}
{$_aRules = $smarty.local.aRules}

{* Правила валидации *}
{if $smarty.local.sEntity}
	{field_make_rule entity=$smarty.local.sEntity field=$smarty.local.sEntityField|default:$smarty.local.sName scenario=$smarty.local.sEntityScenario assign=_aRules}
{/if}

{**
 * Получение значения атрибута value
 *}
{function field_input_attr_value}
{strip}
	{if $_sValue}
		{$_sValue}
	{elseif isset($_aRequest[$smarty.local.sName])}
		{$_aRequest[$smarty.local.sName]}
	{/if}
{/strip}
{/function}

{**
 * Общие атрибуты
 *}
{function field_input_attr_common bUseValue=true}
	id="{$_uid}"
	class="{$_sComponentName}-input {$_sInputClasses}"
	{if $bUseValue}value="{field_input_attr_value}"{/if}
	{if $smarty.local.sName}name="{$smarty.local.sName}"{/if}
	{if $smarty.local.sPlaceholder}placeholder="{$smarty.local.sPlaceholder}"{/if}
	{if $smarty.local.bIsDisabled}disabled{/if}
	{foreach $_aRules as $sRule}
		{if is_bool( $sRule@value ) && ! $sRule@value}{continue}{/if}

		data-{$sRule@key}="{$sRule@value}"
	{/foreach}
	{$_sInputAttributes}
{/function}


{block 'field'}
	<div class="{$_sComponentName} {mod name=$_sComponentName mods=$_sMods} clearfix {$smarty.local.sClasses} {block 'field_classes'}{/block}" {$smarty.local.sAttributes}>
		{* Лэйбл *}
		{if $smarty.local.sLabel}
			<label for="{$_uid}" class="{$_sComponentName}-label">{$smarty.local.sLabel}</label>
		{/if}

		{* Блок с инпутом *}
		<div class="{$_sComponentName}-holder">
			{block 'field_input'}{/block}
		</div>

		{* Подсказка *}
		{if $smarty.local.sNote}
			<small class="{$_sComponentName}-note js-{$_sComponentName}-note">{$smarty.local.sNote}</small>
		{/if}
	</div>
{/block}