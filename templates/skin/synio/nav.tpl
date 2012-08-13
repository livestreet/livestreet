<nav id="nav">
	{if $menu}
		{if in_array($menu,$aMenuContainers)}{$aMenuFetch.$menu}{else}{include file="menu.$menu.tpl"}{/if}
	{/if}
	
	{if $oUserCurrent}
		<a href="{router page='topic'}add/" class="button button-write js-write-window-show" id="modal_write_show">{$aLang.block_create}</a>
	{/if}
	
	<div class="search-header">
		<div class="search-header-show" id="search-header-show"><i class="icon-synio-search"></i> <a href="#" class="link-dotted">{$aLang.search_submit}</a></div>
		
		<form class="search-header-form" id="search-header-form" action="{router page='search'}topics/" style="display: none">
			<input type="text" placeholder="{$aLang.search}" maxlength="255" name="q" class="input-text">
			<input type="submit" value="" title="{$aLang.search_submit}" class="input-submit">
		</form>
	</div>
</nav>