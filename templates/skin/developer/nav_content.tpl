{if $menu or $menu_content}
	<div class="nav-group">
		{if $menu}
			{if in_array($menu,$aMenuContainers)}{$aMenuFetch.$menu}{else}{include file="menu.$menu.tpl"}{/if}
		{/if}
		
		{if $menu_content}
			{include file="menu.$menu_content.content.tpl"}
		{/if}
	</div>
{/if}