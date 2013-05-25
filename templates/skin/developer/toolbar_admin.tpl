{**
 * Тулбар
 * Кнопка перехода в админку
 *
 * @styles css/toolbar.css
 * @scripts js/livestreet/toolbar.js
 *}

{if $oUserCurrent and $oUserCurrent->isAdministrator()}
	<section class="toolbar-admin">
		<a href="{router page='admin'}" title="{$aLang.admin_title}">
			<i class="icon-cog"></i>
		</a>
	</section>
{/if}