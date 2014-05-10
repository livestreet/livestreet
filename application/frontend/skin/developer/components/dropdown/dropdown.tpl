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
{include 'components/button/button.tpl'
		 sClasses    = "{$_sComponentName}-toggle js-dropdown-default {$smarty.local.sClasses}"
		 sAttributes = "data-{$_sComponentName}-target=\"js-{$_sComponentName}-{$_sName}-menu\" {$smarty.local.sAttributes}"
		 sText       = $smarty.local.sText}

{* Выпадающее меню *}
{include './dropdown.menu.tpl'
		 sName          = "{$_sName}_menu"
		 sActiveItem    = $smarty.local.sActiveItem
		 sAttributes    = "id=\"js-{$_sComponentName}-{$_sName}-menu\""
		 aItems         = $smarty.local.aMenu}