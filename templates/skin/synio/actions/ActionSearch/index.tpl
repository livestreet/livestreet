{include file='header.tpl'}

<h2 class="page-header">{$aLang.search}</h2>

{hook run='search_begin'}

<form action="{router page='search'}topics/" class="search">
	{hook run='search_form_begin'}
	<input type="text" placeholder="{$aLang.search}" maxlength="255" name="q" class="input-text">
	<input type="submit" value="" title="{$aLang.search_submit}" class="input-submit icon icon-search">
	{hook run='search_form_end'}
</form>

{hook run='search_end'}

{include file='footer.tpl'}