{include file='header.tpl' showWhiteBack=true}

<h1>{$aLang.search}</h1>
<form action="{router page='search'}topics/" method="GET">
	<p>
		<input type="text" value="" name="q" class="w300">
		<input type="submit" value="{$aLang.search_submit}">
	</p>
</form>

{include file='footer.tpl'}