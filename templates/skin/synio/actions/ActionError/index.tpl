{assign var="noSidebar" value=true}
{include file='header.tpl' noShowSystemMessage=true}


<div class="content-error">
	{if $aMsgError[0].title}
		<h2 class="page-header">{$aLang.error}: <span>{$aMsgError[0].title}</span></h2>
	{/if}

	<p>{$aMsgError[0].msg}</p>
	<br />
	<br />
	<p><a href="javascript:history.go(-1);">{$aLang.site_history_back}</a><br />
	<a href="{cfg name='path.root.web'}">{$aLang.site_go_main}</a></p>
</div>


{include file='footer.tpl'}