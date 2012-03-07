{include file='header.tpl'}


<h2 class="page-header">{$aLang.search}</h2>

<form action="{router page='search'}topics/" class="search">
	<input type="text" placeholder="{$aLang.search}" maxlength="255" name="q" class="input-text">
	<input type="submit" value="" title="{$aLang.search_submit}" class="input-submit icon icon-search">
</form>


{include file='footer.tpl'}