{**
 * Выпадающее меню
 *
 * @param string sName
 * @param string sText
 * @param string sClasses
 * @param string sAttributes
 * @param string sActiveItem
 * @param array  aItems
 *}

{include 'components/nav/nav.tpl'
		 sName          = "{$smarty.local.sName}_menu"
		 sActiveItem    = $smarty.local.sActiveItem
		 sMods          = 'stacked dropdown'
		 sClasses       = "dropdown-menu {$smarty.local.sClasses}"
		 sAttributes    = $smarty.local.sAttributes
		 aItems         = $smarty.local.aItems}