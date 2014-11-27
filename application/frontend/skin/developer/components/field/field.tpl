{**
 * Базовый шаблон поля формы
 *
 * @param string  name      Имя поля (параметр name)
 * @param string  label     Текст лэйбла
 * @param string  note      Подсказка (отображается под полем)
 * @param string  rules     Правила валидации
 *}

{* Название компонента *}
{$component = 'field'}

{block 'field_options'}
	{* Уникальный ID *}
	{$_uid = $smarty.local.id|default:($component|cat:rand(0, 10e10))}

	{* Переменные *}
	{$_mods = $smarty.local.mods}
	{$_value = $smarty.local.value}
	{$_inputClasses = $smarty.local.inputClasses}
	{$_attributes = $smarty.local.attributes}
	{$_inputAttributes = $smarty.local.inputAttributes}
	{$_rules = $smarty.local.rules|default:[]}
	{$name = $smarty.local.name}
	{$label = $smarty.local.label}
{/block}

{* Правила валидации *}
{if $smarty.local.entity}
	{field_make_rule entity=$smarty.local.entity field=$smarty.local.entityField|default:$name scenario=$smarty.local.entityScenario assign=_rules}
{/if}

{**
 * Получение значения атрибута value
 *}
{function field_input_attr_value}
{strip}
	{if $_value}
		{$_value}
	{elseif isset($_aRequest[ $name ])}
		{$_aRequest[ $name ]}
	{/if}
{/strip}
{/function}

{**
 * Общие атрибуты
 *}
{function field_input_attr_common useValue=true}
	id="{$_uid}"
	class="{$component}-input {$_inputClasses}"
	{if $useValue}value="{field_input_attr_value}"{/if}
	{if $name}name="{$name}"{/if}
	{if $smarty.local.placeholder}placeholder="{$smarty.local.placeholder}"{/if}
	{if $smarty.local.isDisabled}disabled{/if}
	{foreach $_rules as $rule}
		{if is_bool( $rule@value ) && ! $rule@value}
			{continue}
		{/if}

		data-{$rule@key}="{$rule@value}"
	{/foreach}
	{cattr list=$_inputAttributes}
{/function}


{block 'field'}
	<div class="{$component} {cmods name=$component mods=$_mods} clearfix {$smarty.local.classes} {block 'field_classes'}{/block}" {cattr list=$_attributes}>
		{* Лэйбл *}
		{if $label}
			<label for="{$_uid}" class="{$component}-label">{$label}</label>
		{/if}

		{* Блок с инпутом *}
		<div class="{$component}-holder">
			{block 'field_input'}{/block}
		</div>

		{* Подсказка *}
		{if $smarty.local.note}
			<small class="{$component}-note js-{$component}-note">{$smarty.local.note}</small>
		{/if}
	</div>
{/block}