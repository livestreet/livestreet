{include file='header.tpl' showWhiteBack=true}

<h1>{$aLang.search}</h1>
<form action="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_SEARCH}/topics/" method="GET">
	<p>
		<input type="text" value="" name="q" class="w300">
		<input type="submit" value="{$aLang.search_submit}">
	</p>
</form>

{include file='footer.tpl'}