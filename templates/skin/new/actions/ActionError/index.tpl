{include file='header.light.tpl' noShowSystemMessage=true}

	<div class="lite-center error">
		{if $aMsgError[0].title}
			<h1>{$aLang.error}: {$aMsgError[0].title}</h1>
		{/if}
		<p>{$aMsgError[0].msg}</p>
		<p><a href="javascript:history.go(-1);">{$aLang.site_history_back}</a>, <a href="{cfg name='path.root.web'}">{$aLang.site_go_main}</a></p>
	</div>

{include file='footer.light.tpl'}