{**
 * Форма поиска
 *
 * @styles components/search-form/search-form.css
 *
 * TODO: Fix submit icon position
 *}

{* Название компонента *}
{$_sComponentName = 'search-form'}

<form action="{$smarty.local.sAction}" method="{$smarty.local.sMethod|default:'get'}" class="{$_sComponentName} {mod name=$_sComponentName mods=$smarty.local.sMods} {$smarty.local.sClasses}" {$smarty.local.sAttributes}>
	{block 'search_form'}
		{include 'components/field/field.text.tpl'
				sPlaceholder  = ( $smarty.local.sPlaceholder ) ? $smarty.local.sPlaceholder : $aLang.search.search
				sNote         = $smarty.local.sNote
				sValue        = $smarty.local.sValue
				sInputClasses = "{$_sComponentName}-input {$smarty.local.sInputClasses}"
				sInputAttributes   = $smarty.local.sInputAttributes
				sName         = $smarty.local.sInputName|default:'q'}

		{if ! $bNoSubmitButton}
			{include 'components/button/button.tpl' mods='icon' classes="{$_sComponentName}-submit" icon='icon-search'}
		{/if}
	{/block}
</form>