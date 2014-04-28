{**
 * Выпадающее меню
 *
 * @param string sName
 * @param string sText
 * @param string sActiveItem
 * @param array  aMenu
 *}

{* Название компонента *}
{$_sComponentName = 'dropdown'}

{* Дефолтные значения *}
{$_sName = ($smarty.local.sName) ? $smarty.local.sName : rand(0, 9999999)}


{* Кнопка *}
{include 'forms/fields/form.field.button.tpl'
		 sFieldClasses    = "{$_sComponentName}-toggle js-dropdown-default {$smarty.local.sClasses}"
		 sFieldAttributes = "data-{$_sComponentName}-target=\"js-{$_sComponentName}-{$_sName}-menu\" {$smarty.local.sAttributes}"
		 sFieldText       = $smarty.local.sText}

{* Выпадающее меню *}
{include './dropdown.menu.tpl'
		 sName          = "{$_sName}_menu"
		 sActiveItem    = $smarty.local.sActiveItem
		 sAttributes    = "id=\"js-{$_sComponentName}-{$_sName}-menu\""
		 aItems         = $smarty.local.aMenu}