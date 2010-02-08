<div id="nav">	
	{if $menu} {include file=menu.$menu.tpl} {/if}

	<div class="search">
		<form action="{router page='search'}topics/" method="post">
			<input class="text" type="text" onblur="if (!value) value=defaultValue" onclick="if (value==defaultValue) value=''" value="{$aLang.search}" name="q" />
			<input class="button" type="submit" value="{$aLang.search}" />
		</form>
	</div>
</div>