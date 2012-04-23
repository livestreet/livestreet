{if $menu}
	{if in_array($menu,$aMenuContainers)}{$aMenuFetch.$menu}{else}{include file="menu.$menu.tpl"}{/if}
{/if}