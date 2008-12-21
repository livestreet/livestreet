{include file='header.tpl'}

<h1>Поиск</h1>
<form action="{$DIR_WEB_ROOT}/search/topics/" method="GET">
	<p>
		<input type="text" value="" name="q" size="250">
		<input type="submit" value="Найти">
	</p>
</form>

{include file='footer.tpl'}
