{**
 * Навигация по результатам поиска
 *}

{$aItems = []}

{foreach $aRes.aCounts as $sType => $iCount}
	{$aItems[] = [ 'name' => $sType, 'url' => "{router page='search'}{$sType}/?q={$aReq.q|escape:'html'}", 'text' => $aLang["search_results_count_$sType"], 'count' => $iCount ]}
{/foreach}

{include 'components/nav/nav.tpl'
		 sName       = 'search'
		 sActiveItem = $aReq.sType
		 sMods       = 'pills'
		 aItems      = $aItems}