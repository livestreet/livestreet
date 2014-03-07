{**
 * Блок сортировки
 *
 * @param string  $sSortName     
 * @param array   $aSortList     
 * @param string  $sSortOrder    
 * @param string  $sSortOrderWay 
 * @param string  $sSortLabel    
 * @param boolean $bSortShowLabel
 *
 * @styles assets/css/sort.css
 *}

<div class="sort js-search-sort {$sSortClasses}">
	{if $bSortShowLabel|default:true}
		<div class="sort-label">{if $sSortLabel}{$sSortLabel}{else}{$aLang.sort.label}{/if}</div>
	{/if}

	<div class="dropdown dropdown-toggle js-dropdown-default" data-dropdown-target="js-dropdown-sort-{$sSortName}" data-dropdown-selectable="true">...</div>

	<ul class="dropdown-menu js-search-sort-menu" id="js-dropdown-sort-{$sSortName}">
		{foreach $aSortList as $aSortItem}
			<li class="sort-item {if $aSortItem@index == 0}active{/if}" data-search-type="{$sSortSearchType}" data-name="sort_by" data-value="{$aSortItem['name']}" data-order="{if $aSortItem['order']}{$aSortItem['order']}{else}desc{/if}">
				<a href="#">
					{$aSortItem['text']}
				</a>
			</li>
		{/foreach}
	</ul>
</div>