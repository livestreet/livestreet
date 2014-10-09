{**
 * Выпадающее меню
 *
 * @param string name
 * @param string text
 * @param string classes
 * @param string attributes
 * @param string activeItem
 * @param array  items
 *}

{include 'components/nav/nav.tpl'
	sName       = "{$smarty.local.name}_menu"
	sActiveItem = $smarty.local.activeItem
	sMods       = 'stacked dropdown'
	sClasses    = "dropdown-menu {$smarty.local.classes}"
	sAttributes = "{$smarty.local.attributes} id=\"{$smarty.local.id}\""
	aItems      = $smarty.local.items}