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
	name       = "{$smarty.local.name}_menu"
	activeItem = $smarty.local.activeItem
	mods       = 'stacked dropdown'
	classes    = "dropdown-menu {$smarty.local.classes}"
	attributes = "{$smarty.local.attributes} id=\"{$smarty.local.id}\""
	items      = $smarty.local.items}