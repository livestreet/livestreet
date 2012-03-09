<aside class="toolbar">
	{include file='blocks.tpl' group='toolbar'}

	{if $oUserCurrent and $oUserCurrent->isAdministrator()}
		<section class="toolbar-admin">
			<a href="{router page='admin'}" title="{$aLang.admin_title}"></a>
		</section>
	{/if}
</aside>