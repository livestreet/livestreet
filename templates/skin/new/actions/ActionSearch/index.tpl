{include file='header.tpl' showWhiteBack=true}

<h1>Поиск</h1>
<form action="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_SEARCH}/topics/" method="GET">
	<p>
		<input type="text" value="" name="q" class="w300">
		<input type="submit" value="Найти">
	</p>
</form>

{include file='footer.tpl'}