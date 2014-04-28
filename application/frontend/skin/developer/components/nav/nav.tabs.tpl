{**
 * Табы
 *}

{foreach $smarty.local.aItems as $aItem}
	{$aItem['attributes'] = "data-type=\"tab\" data-tab-url=\"{$aItem['url']}\" data-tab-target=\"{$aItem['pane']}\""}
	{$aItem['url'] = "#"}

	{$_aTabItems[] = $aItem}
{/foreach}

{include './nav.tpl'
		 sName       = $smarty.local.sName
		 sActiveItem = $smarty.local.sActiveItem
		 sMods       = $smarty.local.sMods
		 sClasses    = $smarty.local.sClasses
		 sAttributes = "data-type=\"tabs\" {$smarty.local.sAttributes}"
		 aItems      = $_aTabItems}