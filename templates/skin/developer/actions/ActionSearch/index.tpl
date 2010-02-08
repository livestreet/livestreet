{include file='header.tpl'}

<h2>{$aLang.search}</h2>
<form action="{router page='search'}topics/" method="GET">
	<p>
		<input type="text" value="" name="q" class="w100p" />
		<input type="submit" value="{$aLang.search_submit}" />
	</p>
</form>

{include file='footer.tpl'}