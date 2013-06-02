<nav id="nav">
	{if $nav}
		{if in_array($nav,$aMenuContainers)}{$aMenuFetch.$nav}{else}{include file="navs/nav.$nav.tpl"}{/if}
	{/if}
	
	{if $oUserCurrent}
		<a href="{router page='topic'}add/" class="button button-write" data-type="modal-toggle" data-option-target="modal-write">{$aLang.block_create}</a>
	{/if}
	
	{**
	 * Форма поиска
	 *
	 * @scripts js/init.js
	 *}
	<div class="search-header">
		<div class="search-header-show" id="search-header-show"><i class="icon-synio-search"></i> <a href="#" class="link-dotted">{$aLang.search_submit}</a></div>
		
		<form class="search-form" id="search-header-form" action="{router page='search'}topics/" style="display: none">
			<div class="search-form-search">
				<input type="text" placeholder="{$aLang.search}" maxlength="255" name="q" class="search-form-input width-250">
				<input type="submit" value="" title="{$aLang.search_submit}" class="search-form-submit">
			</div>
		</form>
	</div>
</nav>