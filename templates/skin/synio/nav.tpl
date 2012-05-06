<nav id="nav">
	{if $menu}
		{if in_array($menu,$aMenuContainers)}{$aMenuFetch.$menu}{else}{include file="menu.$menu.tpl"}{/if}
	{/if}
	
	{if $oUserCurrent}
		<a href="{router page='topic'}add/" class="button button-write" id="modal_write_show">{$aLang.block_create}</a>
	{/if}
</nav>