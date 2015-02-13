{**
 * Форма поиска
 *
 * @styles components/search-form/search-form.css
 *
 * TODO: Fix submit icon position
 *}

{* Название компонента *}
{$component = 'search-form'}

<form action="{$smarty.local.action}" method="{$smarty.local.method|default:'get'}" class="{$component} {cmods name=$component mods=$smarty.local.mods} {$smarty.local.classes}" {cattr list=$smarty.local.attributes}>
	{block 'search_form'}
		{component 'field' template='text'
				placeholder  = ( $smarty.local.placeholder ) ? $smarty.local.placeholder : $aLang.search.search
				note         = $smarty.local.note
				value        = $smarty.local.value
				inputClasses = "{$component}-input {$smarty.local.inputClasses}"
				inputAttributes   = $smarty.local.inputAttributes
				name         = $smarty.local.inputName|default:'q'}

		{if ! $smarty.local.noSubmitButton}
			{component 'button' mods='icon' classes="{$component}-submit" icon='search'}
		{/if}
	{/block}
</form>