{include file='header.tpl'}


<h2>{$aLang.search}</h2>
<form action="{router page='search'}topics/" method="GET">
	<input type="text" value="" name="q" />
	<input type="submit" value="{$aLang.search_submit}" />
</form>


{include file='footer.tpl'}